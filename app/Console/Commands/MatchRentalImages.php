<?php

namespace App\Console\Commands;

use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Kiralık araçlara, aynı marka/modeldeki mevcut (F1RST'ten çekilmiş)
 * araç görsellerini kopyalar. Görsel dosyası rentals/ klasörüne bağımsız
 * kopyalanır (biri silinince diğeri bozulmaz).
 */
class MatchRentalImages extends Command
{
    protected $signature = 'rentals:match-images {--force : Görseli olanları da güncelle}';

    protected $description = 'Kiralık araçlara mevcut araç görsellerinden eşleştirip kopyalar';

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $rentals = Rental::all();
        $ok = 0; $miss = 0;

        foreach ($rentals as $rental) {
            if ($rental->cover_image && ! $this->option('force')) {
                continue;
            }

            $first = explode(' ', trim($rental->model))[0];

            $vehicle = Vehicle::whereNotNull('cover_image')
                ->where('cover_image', 'like', 'vehicles/%')
                ->where('brand', $rental->brand)
                ->where('model', 'like', '%' . $first . '%')
                ->first();

            if (! $vehicle) {
                $vehicle = Vehicle::whereNotNull('cover_image')
                    ->where('cover_image', 'like', 'vehicles/%')
                    ->where('brand', $rental->brand)
                    ->first();
            }

            if (! $vehicle || ! $disk->exists($vehicle->cover_image)) {
                $this->warn("  eşleşme yok: {$rental->brand} {$rental->model}");
                $miss++;
                continue;
            }

            $ext  = pathinfo($vehicle->cover_image, PATHINFO_EXTENSION);
            $dest = "rentals/{$rental->id}.{$ext}";
            $disk->put($dest, $disk->get($vehicle->cover_image));
            $rental->update(['cover_image' => $dest]);

            $this->line("  {$rental->brand} {$rental->model}  ←  {$vehicle->model}");
            $ok++;
        }

        $this->info("Bitti. Eşleşen: {$ok}, Eşleşmeyen: {$miss}");

        return self::SUCCESS;
    }
}
