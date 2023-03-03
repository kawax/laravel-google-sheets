<?php

namespace Revolution\Google\Sheets\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Revolution\Google\Sheets\Contracts\Factory;
use Revolution\Google\Sheets\Sheets;

class SheetsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, Sheets::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @codeCoverageIgnore
     */
    public function provides(): array
    {
        return [Factory::class];
    }
}
