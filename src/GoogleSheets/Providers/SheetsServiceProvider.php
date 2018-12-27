<?php

namespace Revolution\Google\Sheets\Providers;

use Illuminate\Support\ServiceProvider;

use Revolution\Google\Sheets\Sheets;
use Revolution\Google\Sheets\Contracts\Factory;

class SheetsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new Sheets();
        });

        $this->app->alias(
            Sheets::class, Factory::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [Factory::class];
    }
}
