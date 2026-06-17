<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Part;
use App\Models\PartCategory;
use App\Models\Post;
use App\Models\Rental;
use App\Models\SellRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Console\Command;

class TestReset extends Command
{
    protected $signature = 'test:reset';

    protected $description = 'Playwright testlerinin bıraktığı verileri temizler ve seed/stok durumunu geri yükler (yalnızca PWTEST* / *@test.com kayıtları)';

    public function handle(): int
    {
        // Test e-postalı siparişler + kalemleri
        $orderIds = Order::where('email', 'like', '%@test.com')->pluck('id');
        $oi = OrderItem::whereIn('order_id', $orderIds)->delete();
        $o  = Order::whereIn('id', $orderIds)->delete();

        $v  = Vehicle::where('brand', 'like', 'PWTEST%')->delete();
        $r  = Rental::where('brand', 'like', 'PWTEST%')->delete();
        $p  = Part::where('name', 'like', 'PWTEST%')->delete();
        $po = Post::where('title_tr', 'like', 'PWTEST%')->delete();
        $sr = SellRequest::where('email', 'like', '%@test.com')->delete();
        $u  = User::where('email', 'like', '%@test.com')->delete();
        $c  = PartCategory::where('name', 'like', 'PW Kategori%')->delete();

        // Seed parçalarının stoğunu/orijinal değerlerini geri yükle (updateOrCreate → idempotent)
        $this->call('db:seed', ['--class' => 'PartSeeder', '--force' => true]);

        $this->info("Temizlendi → araç:$v kiralık:$r parça:$p yazı:$po sat:$sr üye:$u kategori:$c sipariş:$o (kalem:$oi). Stok geri yüklendi.");

        return self::SUCCESS;
    }
}
