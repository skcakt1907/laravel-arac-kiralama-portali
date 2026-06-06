<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * F1RST Motors (referans site) araç verilerini çeker.
 *
 * Kaynak: https://f1rstmotors.com/api/v1/en/home/dynamic-cars-sitemap (URL listesi)
 * Her /sales/{slug} sayfasındaki JSON-LD (schema.org Car) bloğundan
 * marka/model/yıl/km/motor/şanzıman/renk/açıklama/görsel okunur.
 *
 * NOT: Fiyat brief gereği SİTEDE GÖSTERİLMEZ (vehicles tablosunda fiyat yok).
 */
class ScrapeF1rstVehicles extends Command
{
    protected $signature = 'vehicles:scrape
        {--limit=0 : Kaç araç çekilsin (0 = tümü)}
        {--offset=0 : Baştan kaç araç atlansın}
        {--sleep=400 : İstekler arası bekleme (ms)}
        {--fresh : Çekmeden önce mevcut araçları sil}';

    protected $description = 'F1RST Motors referans sitesinden araç verilerini çeker';

    private const SITEMAP = 'https://f1rstmotors.com/api/v1/en/home/dynamic-cars-sitemap';
    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            Vehicle::query()->delete();
            $this->warn('Mevcut araçlar silindi.');
        }

        $this->info('Sitemap çekiliyor...');
        $xml = Http::withHeaders(['User-Agent' => self::UA])->timeout(30)->get(self::SITEMAP)->body();

        preg_match_all('#https://f1rstmotors\.com/sales/([a-z0-9-]+)#i', $xml, $m);
        $slugs = array_values(array_unique($m[1]));

        $offset = (int) $this->option('offset');
        $limit  = (int) $this->option('limit');
        $slugs  = array_slice($slugs, $offset);
        if ($limit > 0) {
            $slugs = array_slice($slugs, 0, $limit);
        }

        $total = count($slugs);
        if ($total === 0) {
            $this->error('Sitemap boş döndü veya araç bulunamadı.');
            return self::FAILURE;
        }

        $this->info("İşlenecek araç: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $ok = 0; $fail = 0; $sleep = (int) $this->option('sleep') * 1000;

        foreach ($slugs as $i => $slug) {
            try {
                $data = $this->fetchVehicle($slug);
                if ($data) {
                    Vehicle::updateOrCreate(['slug' => $slug], $data);
                    $ok++;
                } else {
                    $fail++;
                }
            } catch (\Throwable $e) {
                $fail++;
                $this->newLine();
                $this->warn("  {$slug}: " . $e->getMessage());
            }

            $bar->advance();
            if ($sleep > 0 && $i < $total - 1) {
                usleep($sleep);
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Bitti. Başarılı: {$ok}, Hatalı: {$fail}, DB toplam: " . Vehicle::count());

        return self::SUCCESS;
    }

    private function fetchVehicle(string $slug): ?array
    {
        $html = Http::withHeaders(['User-Agent' => self::UA])
            ->timeout(30)
            ->get("https://f1rstmotors.com/sales/{$slug}")
            ->body();

        $ld = $this->extractJsonLd($html);
        if (! $ld || ! isset($ld['brand']['name'])) {
            return null;
        }

        $engine = $ld['vehicleEngine']['name'] ?? null;
        $power  = $ld['vehicleEngine']['enginePower'] ?? null;
        if ($engine && $power) {
            $engine .= ' · ' . $power;
        }

        return [
            'brand'        => trim($ld['brand']['name']),
            'model'        => trim($ld['model'] ?? $ld['name'] ?? $slug),
            'year'         => isset($ld['vehicleModelDate']) ? (int) $ld['vehicleModelDate'] : null,
            'mileage_km'   => isset($ld['mileageFromOdometer']['value']) ? (int) $ld['mileageFromOdometer']['value'] : null,
            'engine'       => $engine,
            'transmission' => $ld['vehicleEngine']['transmissionType']['value'] ?? null,
            'color'        => isset($ld['color']) ? trim($ld['color']) : null,
            'description'  => $this->cleanText($ld['description'] ?? null),
            'cover_image'  => $ld['image'] ?? null,   // harici S3 URL
            'source_url'   => "https://f1rstmotors.com/sales/{$slug}",
            'is_published' => true,
        ];
    }

    /** RSC payload'ından JSON-LD (schema.org Car) objesini ayıklar */
    private function extractJsonLd(string $html): ?array
    {
        // 1) Klasik <script type="application/ld+json">
        if (preg_match('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $html, $mm)) {
            $obj = json_decode(trim($mm[1]), true);
            if (is_array($obj) && ($obj['@type'] ?? null) === 'Car') {
                return $obj;
            }
        }

        // 2) Next.js RSC: self.__next_f.push([1,"..."]) parçalarını birleştir
        preg_match_all('/self\.__next_f\.push\(\[1,("(?:[^"\\\\]|\\\\.)*")\]\)/s', $html, $parts);
        $buf = '';
        foreach ($parts[1] as $p) {
            $decoded = json_decode($p);
            if (is_string($decoded)) {
                $buf .= $decoded;
            }
        }

        $i = strpos($buf, '"@context"');
        if ($i === false) {
            return null;
        }
        $start = strrpos(substr($buf, 0, $i), '{');
        if ($start === false) {
            return null;
        }

        // dengeli süslü parantezle objeyi kes
        $depth = 0;
        for ($j = $start, $len = strlen($buf); $j < $len; $j++) {
            if ($buf[$j] === '{') {
                $depth++;
            } elseif ($buf[$j] === '}') {
                $depth--;
                if ($depth === 0) {
                    $obj = json_decode(substr($buf, $start, $j - $start + 1), true);
                    return is_array($obj) ? $obj : null;
                }
            }
        }

        return null;
    }

    private function cleanText(?string $text): ?string
    {
        if (! $text) {
            return null;
        }
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\u{00a0}", ' ', $text);
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}
