<?php

namespace Revolution\Google\Sheets\Traits;

use Illuminate\Container\Container;
use Revolution\Google\Sheets\Contracts\Factory;

/**
 * use at User model.
 */
trait GoogleSheets
{
    /**
     * @return Factory
     */
    public function sheets()
    {
        $token = $this->sheetsAccessToken();

        return Container::getInstance()->make(Factory::class)->setAccessToken($token);
    }

    /**
     * Get the Access Token.
     *
     * @return string|array
     */
    abstract protected function sheetsAccessToken();
}
