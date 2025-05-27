<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        //
        Response::macro('nocache', function ($response) {
            return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', '0');
        });
    }
}
