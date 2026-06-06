<?php

namespace App\Providers;

use App\Models\PartCategory;
use App\Models\Vehicle;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tema uyumlu sayfalama görünümü (siyah-altın)
        Paginator::defaultView('vendor.pagination.site');
        Paginator::defaultSimpleView('vendor.pagination.site');

        // Mega menü için marka + kategori verisi (her sayfada navbar'da)
        View::composer('layouts.app', function ($view) {
            $view->with('navBrands', Vehicle::published()
                ->selectRaw('brand, count(*) as c')
                ->groupBy('brand')
                ->orderByDesc('c')
                ->get());
            $view->with('navCategories', PartCategory::orderBy('sort_order')->orderBy('name')->get());
        });
    }
}
