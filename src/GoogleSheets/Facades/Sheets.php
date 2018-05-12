<?php

namespace Revolution\Google\Sheets\Facades;

use Illuminate\Support\Facades\Facade;

use Revolution\Google\Sheets\SheetsInterface;

class Sheets extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SheetsInterface::class;
    }
}
