<?php

namespace Revolution\Google\Sheets\Traits;

use Revolution\Google\Sheets\SheetsInterface;

/**
 * use at User model
 */
trait GoogleSheets
{
    /**
     * @return SheetsInterface
     */
    public function sheets()
    {
        $token = $this->sheetsAccessToken();

        return app(SheetsInterface::class)->setAccessToken($token);
    }

    /**
     * Get the Access Token
     *
     * @return string|array
     */
    abstract protected function sheetsAccessToken();
}
