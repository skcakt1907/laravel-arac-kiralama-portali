<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Araç kapak görsellerini harici (F1RST S3) URL'den indirip
 * yerel diske (storage/app/public/vehicles) kaydeder ve
 * cover_image alanını yerel yola çevirir. Böylece hotlink kalmaz.
 */
class DownloadVehicleImages extends Command
{
    protected $signature = 'vehicles:download-images
        {--limit=0 : Kaç araç işlensin (0 = tümü)}
        {--sleep=200 : İstekler arası bekleme (ms)}
        {--force : Yerele inmiş olsa bile yeniden indir}';

    protected $description = 'Araç kapak görsellerini yerel diske indirir (hotlink kaldırır)';

    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    public function handle(): int
    {
        $query = Vehicle::query()->whereNotNull('cover_image');
        if (! $this->option('force')) {
            $query->where('cover_image', 'like', 'http%'); // sadece harici olanlar
        }
        if ($limit = (int) $this->option('limit')) {
            $query->limit($limit);
        }

        $vehicles = $query->get();
        $total = $vehicles->count();
        if ($total === 0) {
            $this->info('İndirilecek harici görsel yok. Hepsi zaten yerel.');
            return self::SUCCESS;
        }

        $this->info("İndirilecek görsel: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $ok = 0; $fail = 0; $sleep = (int) $this->option('sleep') * 1000;

        foreach ($vehicles as $vehicle) {
            $src = $vehicle->cover_image;
            if (! str_starts_with($src, 'http')) {
                $bar->advance();
                continue;
            }

            try {
                $res = Http::withHeaders(['User-Agent' => self::UA])->timeout(40)->get($src);
                if (! $res->successful()) {
                    throw new \RuntimeException('HTTP ' . $res->status());
                }

                $ext  = $this->guessExtension($res->header('Content-Type'));
                $path = "vehicles/{$vehicle->id}.{$ext}";
                Storage::disk('public')->put($path, $res->body());

                $vehicle->update(['cover_image' => $path]);
                $ok++;
            } catch (\Throwable $e) {
                $fail++;
                $this->newLine();
                $this->warn("  #{$vehicle->id} {$vehicle->slug}: " . $e->getMessage());
            }

            $bar->advance();
            if ($sleep > 0) {
                usleep($sleep);
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Bitti. İndirilen: {$ok}, Hatalı: {$fail}");

        return self::SUCCESS;
    }

    private function guessExtension(?string $contentType): string
    {
        return match (true) {
            str_contains((string) $contentType, 'png')  => 'png',
            str_contains((string) $contentType, 'webp') => 'webp',
            str_contains((string) $contentType, 'avif') => 'avif',
            str_contains((string) $contentType, 'gif')  => 'gif',
            default => 'jpg',
        };
    }
}
