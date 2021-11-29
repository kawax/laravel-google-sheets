<?php

namespace Revolution\Google\Sheets;

use Google\Service;
use Google\Service\Sheets as GoogleSheets;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use PulkitJalan\Google\Client;
use Revolution\Google\Sheets\Contracts\Factory;

class Sheets implements Factory
{
    use Concerns\SheetsValues;
    use Concerns\SheetsDrive;
    use Concerns\SheetsProperties;
    use Concerns\SheetsCollection;
    use Macroable {
        __call as macroCall;
    }

    /**
     * @var GoogleSheets
     */
    protected $service;

    /**
     * @var string
     */
    protected $spreadsheetId;

    /**
     * @var string
     */
    protected $sheet;

    /**
     * @param  GoogleSheets|Service  $service
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return GoogleSheets
     */
    public function getService(): GoogleSheets
    {
        if (is_null($this->service)) {
            $this->service = Container::getInstance()->make(Client::class)->make('sheets');
        }

        return $this->service;
    }

    /**
     * set access_token and set new service.
     *
     * @param  string|array  $token
     * @return $this
     *
     * @throws \Exception
     */
    public function setAccessToken($token)
    {
        /**
         * @var Client $google
         */
        $google = Container::getInstance()->make(Client::class);

        $google->getCache()->clear();

        $google->setAccessToken($token);

        if (isset($token['refresh_token']) and $google->isAccessTokenExpired()) {
            $google->fetchAccessTokenWithRefreshToken();
        }

        return $this->setService($google->make('sheets'))
                    ->setDriveService($google->make('drive'));
    }

    /**
     * @return array
     */
    public function getAccessToken()
    {
        return $this->getService()->getClient()->getAccessToken();
    }

    /**
     * @param  string  $spreadsheetId
     * @return $this
     */
    public function spreadsheet(string $spreadsheetId)
    {
        $this->spreadsheetId = $spreadsheetId;

        return $this;
    }

    /**
     * @param  string  $title
     * @return $this
     */
    public function spreadsheetByTitle(string $title)
    {
        $list = $this->spreadsheetList();
        $id = Arr::get(array_flip($list), $title);

        $this->spreadsheetId = $id;

        return $this;
    }

    /**
     * @param  string  $sheet
     * @return $this
     */
    public function sheet(string $sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @param  string  $sheetId
     * @return $this
     */
    public function sheetById(string $sheetId)
    {
        $list = $this->sheetList();

        $sheet = Arr::get($list, $sheetId);

        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @return array
     */
    public function sheetList(): array
    {
        $list = [];

        $sheets = $this->getService()->spreadsheets->get($this->spreadsheetId)->getSheets();

        foreach ($sheets as $sheet) {
            $list[$sheet->getProperties()->getSheetId()] = $sheet->getProperties()->getTitle();
        }

        return $list;
    }

    /**
     * @param  string  $sheetTitle
     * @return \Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function addSheet(string $sheetTitle)
    {
        $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            [
                'requests' => [
                    'addSheet' => [
                        'properties' => [
                            'title' => $sheetTitle,
                        ],
                    ],
                ],
            ]
        );

        return $this->getService()->spreadsheets->batchUpdate($this->spreadsheetId, $body);
    }

    /**
     * @param  string  $sheetTitle
     * @return \Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function deleteSheet(string $sheetTitle)
    {
        $list = $this->sheetList();
        $id = Arr::get(array_flip($list), $sheetTitle);

        $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            [
                'requests' => [
                    'deleteSheet' => [
                        'sheetId' => $id,
                    ],
                ],
            ]
        );

        return $this->getService()->spreadsheets->batchUpdate($this->spreadsheetId, $body);
    }

    /**
     * @param  string  $property
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __get($property)
    {
        if (property_exists($this->getService(), $property)) {
            return $this->getService()->{$property};
        }

        throw new \InvalidArgumentException(sprintf('Property [%s] does not exist.', $property));
    }

    /**
     * Magic call method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->getService(), $method)) {
            return $this->getService()->{$method}(...array_values($parameters));
        }

        return $this->macroCall($method, $parameters);
    }
}
