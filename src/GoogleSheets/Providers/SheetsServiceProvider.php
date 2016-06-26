<?php
namespace GoogleSheets\Providers;

use Illuminate\Support\ServiceProvider;

use GoogleSheets\SheetsLaravel;

class SheetsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service provider.
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('google.sheets', function ($app) {
            return new SheetsLaravel();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['google.sheets'];
    }
}
