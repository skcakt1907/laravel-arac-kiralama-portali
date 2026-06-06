<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.send');

Route::get('/araclar', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/araclar/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');
