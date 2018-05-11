<?php

namespace Revolution\Google\Sheets\Traits;

use Revolution\Google\Sheets\Facades\Sheets;
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

        return Sheets::setService(Google::make('sheets'))
                     ->setDriveService(Google::make('drive'));
    }

    /**
     * Get the Access Token
     *
     * @return null|string
     */
    abstract protected function sheetsAccessToken();
}
