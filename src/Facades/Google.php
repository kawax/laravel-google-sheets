<?php

namespace Revolution\Google\Sheets\Facades;

use Illuminate\Support\Facades\Facade;
use Revolution\Google\Sheets\GoogleSheetClient;

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
    protected static function getFacadeAccessor(): string
    {
        return GoogleSheetClient::class;
    }
}
