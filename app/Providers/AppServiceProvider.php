<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind a custom Guzzle client that disables SSL verification locally
        $this->app->singleton(GuzzleClient::class, function ($app) {
            return new GuzzleClient([
                'verify' => false,
            ]);
        });
    }

    public function boot(): void
    {
        //
    }
}