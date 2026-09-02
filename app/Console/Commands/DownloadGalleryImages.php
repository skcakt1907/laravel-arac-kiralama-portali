<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Galeri (vehicle_images) harici S3 görsellerini ORİJİNAL haliyle yerele indirir.
 * path: http... -> vehicles/gallery/{vehicle_id}/{id}.{ext}
 * WebP'ye sonra (yerelde, ağsız) çevrilir: vehicles:gallery-webp-local
 */
class DownloadGalleryImages extends Command
{
    protected $signature = 'vehicles:gallery-download
        {--limit=0 : Kaç görsel (0 = tümü)}
        {--sleep=120 : İstekler arası bekleme (ms)}';

    protected $description = 'Galeri gorsellerini orijinal haliyle yerele indirir';

    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    public function handle(): int
    {
        $q = DB::table('vehicle_images')->where('path', 'like', 'http%')->orderBy('id');
        $limit = (int) $this->option('limit');
        if ($limit > 0) $q->limit($limit);
        $rows = $q->get(['id', 'vehicle_id', 'path']);

        $total = $rows->count();
        if ($total === 0) { $this->info('İndirilecek harici görsel yok.'); return self::SUCCESS; }

        $this->info("İndirilecek: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $disk = Storage::disk('public');
        $sleep = (int) $this->option('sleep') * 1000;
        $ok = 0; $fail = 0;

        foreach ($rows as $i => $row) {
            try {
                $res = Http::withHeaders(['User-Agent' => self::UA])
                    ->timeout(25)->connectTimeout(10)->retry(3, 500)
                    ->get($row->path);

                if (! $res->ok() || strlen($res->body()) < 100) { $fail++; $bar->advance(); continue; }

                $ext = $this->extFromType($res->header('Content-Type'));
                $path = "vehicles/gallery/{$row->vehicle_id}/{$row->id}.{$ext}";
                $disk->put($path, $res->body());

                DB::table('vehicle_images')->where('id', $row->id)->update(['path' => $path, 'updated_at' => now()]);
                $ok++;
            } catch (\Throwable $e) {
                $fail++;
            }
            $bar->advance();
            if ($sleep > 0 && $i < $total - 1) usleep($sleep);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Bitti. İndirilen: {$ok}, hatalı: {$fail}");
        if ($fail > 0) $this->warn("Hatalıları tekrar denemek için komutu yeniden çalıştır (kalanları işler).");

        return self::SUCCESS;
    }

    private function extFromType(?string $type): string
    {
        return match (true) {
            str_contains((string) $type, 'png')  => 'png',
            str_contains((string) $type, 'webp') => 'webp',
            str_contains((string) $type, 'gif')  => 'gif',
            default => 'jpg',
        };
    }
}
