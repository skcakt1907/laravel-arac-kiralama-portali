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
