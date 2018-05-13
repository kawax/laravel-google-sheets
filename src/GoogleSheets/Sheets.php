<?php

namespace Revolution\Google\Sheets;

use Google_Service_Sheets;
use PulkitJalan\Google\Facades\Google;

use Illuminate\Support\Traits\Macroable;

class Sheets implements SheetsInterface
{
    use Traits\SheetsValues;
    use Traits\SheetsDrive;
    use Traits\SheetsProperties;
    use Traits\SheetsCollection;

    use Macroable;

    /**
     * @var \Google_Service_Sheets
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
     * @param Google_Service_Sheets|\Google_Service $service
     *
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Google_Service_Sheets
     */
    public function getService(): Google_Service_Sheets
    {
        return $this->service;
    }

    /**
     * set access_token and set new service
     *
     * @param string|array $token
     *
     * @return $this
     */
    public function setAccessToken($token)
    {
        Google::setAccessToken($token);

        if (isset($token['refresh_token']) and Google::isAccessTokenExpired()) {
            Google::fetchAccessTokenWithRefreshToken();
        }

        return $this->setService(Google::make('sheets'))
                    ->setDriveService(Google::make('drive'));
    }

    /**
     * @param string $spreadsheetId
     *
     * @return $this
     */
    public function spreadsheet(string $spreadsheetId)
    {
        $this->spreadsheetId = $spreadsheetId;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function spreadsheetByTitle(string $title)
    {
        $list = $this->spreadsheetList();
        $id = array_get(array_flip($list), $title);

        $this->spreadsheetId = $id;

        return $this;
    }

    /**
     * @param string $sheet
     *
     * @return $this
     */
    public function sheet(string $sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @param string $sheetId
     *
     * @return $this
     */
    public function sheetById(string $sheetId)
    {
        $list = $this->sheetList();

        $sheet = array_get($list, $sheetId);

        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @return array
     */
    public function sheetList(): array
    {
        $list = [];

        $sheets = $this->service->spreadsheets->get($this->spreadsheetId)->getSheets();

        foreach ($sheets as $sheet) {
            $list[$sheet->getProperties()->getSheetId()] = $sheet->getProperties()->getTitle();
        }

        return $list;
    }

    /**
     * @param string $property
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __get($property)
    {
        if (property_exists($this->service, $property)) {
            return $this->service->{$property};
        }

        throw new \InvalidArgumentException(sprintf('Property [%s] does not exist.', $property));
    }

    /**
     * Magic call method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->service, $method)) {
            return call_user_func_array([$this->service, $method], $parameters);
        }

        if (static::hasMacro($method)) {
            if (static::$macros[$method] instanceof \Closure) {
                return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $parameters);
            }
        }

        throw new \BadMethodCallException(sprintf('Method [%s] does not exist.', $method));
    }
}
