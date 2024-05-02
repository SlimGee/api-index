<?php

namespace App\Providers;

use GeminiAPI\Client;
use Illuminate\Support\ServiceProvider;

class GeminiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(Client::class, function () {
            return new Client(config('services.gemini.key'));
        });
    }
}
