<?php

namespace Revolution\Google\Client\Providers;

use Illuminate\Support\ServiceProvider;
use Revolution\Google\Client\GoogleApiClient;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/google.php', 'google');

        $this->app->scoped(GoogleApiClient::class, fn ($app) => new GoogleApiClient($app['config']['google']));

        $this->app->alias(GoogleApiClient::class, 'google-client');
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../../config/google.php' => config_path('google.php'),
            ], 'google-config');
        }
    }
}
