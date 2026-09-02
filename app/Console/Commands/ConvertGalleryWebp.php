<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Galeri görsellerini F1RST resize servisinden (~300 KB) indirip WebP'ye çevirir.
 * Hem harici (http) hem de yereldeki webp-olmayan (jpg) görselleri işler.
 * Sonuç: vehicles/gallery/{vehicle_id}/{id}.webp (~146 KB)
 */
class ConvertGalleryWebp extends Command
{
    protected $signature = 'vehicles:gallery-webp
        {--quality=80 : WebP kalitesi}
        {--width=1080 : Resize genişliği (resizer)}
        {--sleep=120 : İstekler arası bekleme (ms)}
        {--limit=0 : Kaç görsel (0 = tümü)}';

    protected $description = 'Galeri gorsellerini resizer uzerinden indirip WebP yapar';

    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    public function handle(): int
    {
        if (! function_exists('imagewebp')) { $this->error('GD webp yok.'); return self::FAILURE; }

        // İşlenecek: harici (http) VEYA yerelde webp olmayan
        $q = DB::table('vehicle_images')
            ->where(function ($w) {
                $w->where('path', 'like', 'http%')
                  ->orWhere(fn ($x) => $x->where('path', 'like', 'vehicles/%')->where('path', 'not like', '%.webp'));
            })
            ->orderBy('id');
        $limit = (int) $this->option('limit');
        if ($limit > 0) $q->limit($limit);
        $rows = $q->get(['id', 'vehicle_id', 'path']);

        $total = $rows->count();
        if ($total === 0) { $this->info('İşlenecek görsel yok (hepsi webp).'); return self::SUCCESS; }

        $this->info("İşlenecek: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $disk = Storage::disk('public');
        $quality = (int) $this->option('quality');
        $width = (int) $this->option('width');
        $sleep = (int) $this->option('sleep') * 1000;
        $ok = 0; $fail = 0;

        foreach ($rows as $i => $row) {
            try {
                $isHttp = str_starts_with($row->path, 'http');
                if ($isHttp) {
                    $url = 'https://f1rstmotors.com/_next/image?url=' . urlencode($row->path) . "&w={$width}&q={$quality}";
                    $res = Http::withHeaders(['User-Agent' => self::UA, 'Referer' => 'https://f1rstmotors.com/'])
                        ->timeout(25)->connectTimeout(10)->retry(3, 500)->get($url);
                    if (! $res->ok() || strlen($res->body()) < 100) { $fail++; $bar->advance(); continue; }
                    $bytes = $res->body();
                } else {
                    if (! $disk->exists($row->path)) { $fail++; $bar->advance(); continue; }
                    $bytes = $disk->get($row->path);
                }

                $im = @imagecreatefromstring($bytes);
                if (! $im) { $fail++; $bar->advance(); continue; }
                imagepalettetotruecolor($im);
                imagealphablending($im, true);
                imagesavealpha($im, true);
                ob_start(); imagewebp($im, null, $quality); $data = ob_get_clean();
                imagedestroy($im);

                $new = "vehicles/gallery/{$row->vehicle_id}/{$row->id}.webp";
                $disk->put($new, $data);
                // eski yerel (jpg vb.) dosyayı sil
                if (! $isHttp && $row->path !== $new && $disk->exists($row->path)) {
                    $disk->delete($row->path);
                }
                DB::table('vehicle_images')->where('id', $row->id)->update(['path' => $new, 'updated_at' => now()]);
                $ok++;
            } catch (\Throwable $e) {
                $fail++;
            }
            $bar->advance();
            if ($isHttp ?? false) { if ($sleep > 0 && $i < $total - 1) usleep($sleep); }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Bitti. Çevrilen: {$ok}, hatalı: {$fail}");
        if ($fail > 0) $this->warn('Hatalılar için komutu tekrar çalıştır (kalanları işler).');

        return self::SUCCESS;
    }
}
