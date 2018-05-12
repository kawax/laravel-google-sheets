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
        $token = $this->sheetsAccessToken();

        Google::setAccessToken($token);

        if (isset($token['refresh_token']) and Google::isAccessTokenExpired()) {
            Google::fetchAccessTokenWithRefreshToken();
        }

        return app(SheetsInterface::class)->setService(Google::make('sheets'))
                                          ->setDriveService(Google::make('drive'));
    }

    /**
     * Get the Access Token
     *
     * @return string|array
     */
    abstract protected function sheetsAccessToken();
}
