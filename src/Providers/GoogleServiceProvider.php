<?php

namespace Revolution\Google\Sheets\Providers;

use Illuminate\Support\ServiceProvider;
use Revolution\Google\Sheets\GoogleSheetClient;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../../config/google.php' => config_path('google.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/google.php', 'google');

        $this->app->bind('Revolution\Google\Sheets\GoogleSheetClient', function ($app) {
            return new GoogleSheetClient($app['config']['google']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['Revolution\Google\Sheets\GoogleSheetClient'];
    }
}
