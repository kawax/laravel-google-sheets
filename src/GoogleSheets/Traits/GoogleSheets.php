<?php

namespace Revolution\Google\Sheets\Traits;

use Revolution\Google\Sheets\SheetsInterface;
use PulkitJalan\Google\Facades\Google;

/**
 * use at User model
 */
trait GoogleSheets
{
    /**
     * @return \Revolution\Google\Sheets\Sheets
     * @throws \Exception
     */
    public function sheets()
    {
        Google::setAccessToken($this->sheetsAccessToken());

        return app(SheetsInterface::class)->setService(Google::make('sheets'));
    }

    /**
     * Get the Access Token
     *
     * @return null|string
     */
    abstract public function sheetsAccessToken();
}
