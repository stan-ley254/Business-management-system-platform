<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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

         if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Ensure API-style requests receive JSON on auth/CSRF failures to avoid returning HTML pages
        $handler = $this->app->make(\Illuminate\Contracts\Debug\ExceptionHandler::class);

        // TokenMismatchException -> JSON for API requests
        if (method_exists($handler, 'renderable')) {
            $handler->renderable(function (TokenMismatchException $e, $request) {
                if ($request->expectsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json(['message' => 'CSRF token mismatch. Please refresh and log in again.'], 419);
                }
            });

            // AuthenticationException -> JSON for API requests
            $handler->renderable(function (AuthenticationException $e, $request) {
                if ($request->expectsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
            });
        }

        // Register the standard API rate limiter so 'throttle:api' resolves correctly
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}

