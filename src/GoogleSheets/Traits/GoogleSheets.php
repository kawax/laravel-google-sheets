<?php

namespace Revolution\Google\Sheets\Traits;

use Revolution\Google\Sheets\Contracts\Factory;

/**
 * use at User model
 */
trait GoogleSheets
{
    /**
     * @return Factory
     */
    public function sheets()
    {
        $token = $this->sheetsAccessToken();

        return app(Factory::class)->setAccessToken($token);
    }

    /**
     * Get the Access Token
     *
     * @return string|array
     */
    abstract protected function sheetsAccessToken();
}
