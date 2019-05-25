<?php

namespace Tests;

use Revolution\Google\Sheets\Providers\SheetsServiceProvider;
use Revolution\Google\Sheets\Facades\Sheets;

use PulkitJalan\Google\GoogleServiceProvider;
use PulkitJalan\Google\Facades\Google;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SheetsServiceProvider::class,
            GoogleServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Sheets' => Sheets::class,
            'Google' => Google::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        //
    }
}
