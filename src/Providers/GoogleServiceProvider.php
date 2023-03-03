<?php

namespace Revolution\Google\Sheets\Providers;

use Illuminate\Support\ServiceProvider;
use Revolution\Google\Sheets\GoogleSheetClient;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/google.php', 'google');

        $this->app->singleton(GoogleSheetClient::class, fn ($app) => new GoogleSheetClient($app['config']['google']));
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/google.php' => config_path('google.php'),
            ], 'google-config');
        }
    }
}
