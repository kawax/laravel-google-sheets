<?php

namespace Revolution\Google\Sheets\Facades;

use Illuminate\Support\Facades\Facade;
use Revolution\Google\Sheets\Contracts\Factory;

/**
 * @mixin \Revolution\Google\Sheets\Sheets
 */
class Sheets extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
