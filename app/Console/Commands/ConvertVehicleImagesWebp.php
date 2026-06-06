<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Araç kapak görsellerini (png/jpg) WebP'ye çevirir, cover_image yolunu
 * günceller ve eski dosyayı siler. Disk alanını ciddi şekilde düşürür.
 *
 * Çevirmeden önce yedek alındığından emin ol (ör. PNG'ler zip'lendi).
 */
class ConvertVehicleImagesWebp extends Command
{
    protected $signature = 'vehicles:webp
        {--quality=82 : WebP kalitesi (0-100)}
        {--keep : Eski dosyayı silme}';

    protected $description = 'Araç kapak görsellerini WebP formatına çevirir';

    public function handle(): int
    {
        $quality = (int) $this->option('quality');
        $disk = Storage::disk('public');

        // Yereldeki, webp olmayan kapak görselleri
        $vehicles = Vehicle::where('cover_image', 'like', 'vehicles/%')
            ->where('cover_image', 'not like', '%.webp')
            ->get();

        $total = $vehicles->count();
        if ($total === 0) {
            $this->info('Çevrilecek görsel yok (hepsi zaten WebP).');
            return self::SUCCESS;
        }

        $this->info("Çevrilecek görsel: {$total} (kalite {$quality})");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $ok = 0; $fail = 0; $before = 0; $after = 0;

        foreach ($vehicles as $vehicle) {
            $old = $vehicle->cover_image;
            try {
                if (! $disk->exists($old)) {
                    throw new \RuntimeException('dosya yok');
                }

                $fullOld = $disk->path($old);
                $before += filesize($fullOld);

                $img = $this->loadImage($fullOld);
                if (! $img) {
                    throw new \RuntimeException('okunamadı');
                }
                imagepalettetotruecolor($img);

                $new = preg_replace('/\.[^.]+$/', '.webp', $old);
                $fullNew = $disk->path($new);
                imagewebp($img, $fullNew, $quality);
                imagedestroy($img);

                $after += filesize($fullNew);

                $vehicle->update(['cover_image' => $new]);

                if (! $this->option('keep') && $new !== $old) {
                    $disk->delete($old);
                }
                $ok++;
            } catch (\Throwable $e) {
                $fail++;
                $this->newLine();
                $this->warn("  #{$vehicle->id} {$old}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $mb = fn ($b) => round($b / 1048576, 1);
        $this->info("Bitti. Çevrilen: {$ok}, Hatalı: {$fail}");
        $this->info("Boyut: {$mb($before)} MB → {$mb($after)} MB  (kazanç: " . $mb($before - $after) . " MB)");

        return self::SUCCESS;
    }

    private function loadImage(string $path): \GdImage|false
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'png'          => imagecreatefrompng($path),
            'jpg', 'jpeg'  => imagecreatefromjpeg($path),
            'gif'          => imagecreatefromgif($path),
            'webp'         => imagecreatefromwebp($path),
            default        => false,
        };
    }
}
