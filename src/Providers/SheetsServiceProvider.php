<?php

namespace Revolution\Google\Sheets\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Revolution\Google\Sheets\Contracts\Factory;
use Revolution\Google\Sheets\Sheets;

class SheetsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, Sheets::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [Factory::class];
    }
}
