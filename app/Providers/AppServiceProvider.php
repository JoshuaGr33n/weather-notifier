<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\WeatherRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the repository interface to the implementation
        $this->app->singleton(WeatherRepository::class, function ($app) {
            return new WeatherRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
