<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * F1RST Motors ilan API'sinden araç GALERİ görsellerini çeker.
 *
 * Kaynak: https://f1rstmotors.com/api/v1/en/search/car/urlSlug/{slug}
 *   -> data.car.carImages[].image  (harici S3 URL listesi)
 *
 * Görseller vehicle_images tablosuna (path = S3 URL) yazılır — kapak gibi harici.
 */
class ScrapeF1rstGallery extends Command
{
    protected $signature = 'vehicles:scrape-gallery
        {--limit=0 : Kaç araç işlensin (0 = tümü)}
        {--offset=0 : Baştan kaç araç atlansın}
        {--sleep=350 : İstekler arası bekleme (ms)}
        {--fresh : Mevcut galeriyi silip yeniden çek}
        {--force : Galerisi olan araçları da yeniden işle}';

    protected $description = 'F1RST ilan API uzerinden arac galeri gorsellerini ceker (vehicle_images)';

    private const API = 'https://f1rstmotors.com/api/v1/en/search/car/urlSlug/';
    private const UA  = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    public function handle(): int
    {
        $q = Vehicle::query()->whereNotNull('slug')->orderBy('id');
        $offset = (int) $this->option('offset');
        $limit  = (int) $this->option('limit');
        if ($offset > 0) $q->skip($offset);
        if ($limit > 0)  $q->take($limit);
        $vehicles = $q->get(['id', 'slug']);

        $total = $vehicles->count();
        if ($total === 0) { $this->error('Araç yok.'); return self::FAILURE; }

        $this->info("İşlenecek araç: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $sleep = (int) $this->option('sleep') * 1000;
        $fresh = (bool) $this->option('fresh');
        $force = (bool) $this->option('force') || $fresh;
        $okCars = 0; $imgCount = 0; $skip = 0; $fail = 0;

        foreach ($vehicles as $i => $v) {
            $has = DB::table('vehicle_images')->where('vehicle_id', $v->id)->exists();
            if ($has && ! $force) { $skip++; $bar->advance(); continue; }

            try {
                $urls = $this->fetchGallery($v->slug);
                if (! empty($urls)) {
                    if ($fresh || $has) {
                        DB::table('vehicle_images')->where('vehicle_id', $v->id)->delete();
                    }
                    $rows = [];
                    foreach ($urls as $sira => $url) {
                        $rows[] = [
                            'vehicle_id' => $v->id,
                            'path'       => $url,
                            'sort_order' => $sira,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DB::table('vehicle_images')->insert($rows);
                    $okCars++; $imgCount += count($rows);
                } else {
                    $fail++;
                }
            } catch (\Throwable $e) {
                $fail++;
                $this->newLine();
                $this->warn("  {$v->slug}: " . $e->getMessage());
            }

            $bar->advance();
            if ($sleep > 0 && $i < $total - 1) usleep($sleep);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Bitti. İşlenen araç: {$okCars}, eklenen görsel: {$imgCount}, atlanan (galerisi var): {$skip}, boş/hatalı: {$fail}");

        return self::SUCCESS;
    }

    /** F1RST API'sinden bir aracın galeri (carImages) URL'lerini döndürür. */
    private function fetchGallery(string $slug): array
    {
        $res = Http::withHeaders(['User-Agent' => self::UA, 'Accept' => 'application/json'])
            ->timeout(30)
            ->get(self::API . $slug);

        if (! $res->ok()) return [];

        $car = $res->json('data.car') ?? [];
        $images = $car['carImages'] ?? [];

        $urls = [];
        foreach ($images as $im) {
            $url = $im['image'] ?? null;
            $status = $im['status'] ?? 1;
            if ($url && (int) $status === 1 && ! in_array($url, $urls, true)) {
                $urls[] = $url;
            }
        }
        return $urls;
    }
}
