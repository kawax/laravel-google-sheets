<?php

namespace Revolution\Google\Sheets\Facades;

use Google\Service;
use Illuminate\Support\Facades\Facade;
use Psr\Cache\CacheItemPoolInterface;
use Revolution\Google\Sheets\GoogleSheetClient;

/**
 * @method static Service make(string $service)
 * @method static void setAccessToken(array|string $token)
 * @method static bool isAccessTokenExpired()
 * @method static void fetchAccessTokenWithRefreshToken(?string $refreshToken = null)
 * @method static CacheItemPoolInterface getCache()
 *
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
