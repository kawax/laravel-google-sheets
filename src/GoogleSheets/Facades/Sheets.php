<?php

namespace Revolution\Google\Sheets\Facades;

use Illuminate\Support\Facades\Facade;

class Sheets extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Revolution\Google\Sheets\Sheets::class;
    }
}
