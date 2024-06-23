<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FoodCategory;
use App\Models\AddCashier;
use App\Models\User;


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
    public function boot()
    {
        // Share $categories with all views
        view()->composer('*', function ($view) {
            $view->with('categories', FoodCategory::all());
        });
    }
    
}
