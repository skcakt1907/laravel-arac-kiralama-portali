<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.send');

// Araçlar (vitrin, fiyatsız)
Route::get('/araclar', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/araclar/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

// Mağaza (yedek parça)
Route::get('/magaza', [ShopController::class, 'index'])->name('shop.index');
Route::get('/magaza/{part}', [ShopController::class, 'show'])->name('shop.show');

// Sepet
Route::get('/sepet', [CartController::class, 'index'])->name('cart.index');
Route::post('/sepet/{part}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/sepet/{part}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/sepet/{part}', [CartController::class, 'remove'])->name('cart.remove');

// Sipariş / ödeme
Route::get('/odeme', [CheckoutController::class, 'form'])->name('checkout.form');
Route::post('/odeme', [CheckoutController::class, 'place'])->name('checkout.place');
Route::get('/odeme/{order}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/odeme/{order}/ode', [PaymentController::class, 'pay'])->name('payment.pay');
Route::match(['get', 'post'], '/weobank/callback', [PaymentController::class, 'callback'])->name('payment.callback');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Admin (gizli yönetim paneli)  /yonetim
|--------------------------------------------------------------------------
*/
Route::prefix('yonetim')->name('admin.')->group(function () {
    Route::get('giris', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('giris', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('cikis', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Araçlar
        Route::get('araclar', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'index'])->name('vehicles.index');
        Route::get('araclar/yeni', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'create'])->name('vehicles.create');
        Route::post('araclar', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'store'])->name('vehicles.store');
        Route::get('araclar/{vehicle}/duzenle', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'edit'])->name('vehicles.edit');
        Route::put('araclar/{vehicle}', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'update'])->name('vehicles.update');
        Route::delete('araclar/{vehicle}', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'destroy'])->name('vehicles.destroy');
        Route::patch('araclar/{vehicle}/toggle', [\App\Http\Controllers\Admin\VehicleAdminController::class, 'toggle'])->name('vehicles.toggle');

        // Parçalar
        Route::get('parcalar', [\App\Http\Controllers\Admin\PartAdminController::class, 'index'])->name('parts.index');
        Route::get('parcalar/yeni', [\App\Http\Controllers\Admin\PartAdminController::class, 'create'])->name('parts.create');
        Route::post('parcalar', [\App\Http\Controllers\Admin\PartAdminController::class, 'store'])->name('parts.store');
        Route::get('parcalar/{part}/duzenle', [\App\Http\Controllers\Admin\PartAdminController::class, 'edit'])->name('parts.edit');
        Route::put('parcalar/{part}', [\App\Http\Controllers\Admin\PartAdminController::class, 'update'])->name('parts.update');
        Route::delete('parcalar/{part}', [\App\Http\Controllers\Admin\PartAdminController::class, 'destroy'])->name('parts.destroy');

        // Kategoriler
        Route::get('kategoriler', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'index'])->name('categories.index');
        Route::post('kategoriler', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'store'])->name('categories.store');
        Route::put('kategoriler/{category}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'update'])->name('categories.update');
        Route::delete('kategoriler/{category}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'destroy'])->name('categories.destroy');

        // Siparişler
        Route::get('siparisler', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('siparisler/{order}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
        Route::patch('siparisler/{order}/durum', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.status');
    });
});
