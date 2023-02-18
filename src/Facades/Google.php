<?php

namespace Revolution\Google\Sheets\Facades;

use Revolution\Google\Sheets\GoogleSheetClient;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin GoogleSheetClient
 */
class Google extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GoogleSheetClient::class;
    }
}
