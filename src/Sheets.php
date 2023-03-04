<?php

namespace Revolution\Google\Sheets;

use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\BatchUpdateSpreadsheetResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Revolution\Google\Sheets\Contracts\Factory;
use Revolution\Google\Sheets\Facades\Google;

class Sheets implements Factory
{
    use Concerns\SheetsValues;
    use Concerns\SheetsDrive;
    use Concerns\SheetsProperties;
    use Concerns\SheetsCollection;
    use Conditionable;
    use Macroable {
        __call as macroCall;
    }

    protected ?GoogleSheets $service = null;

    protected ?string $spreadsheetId = null;

    protected ?string $sheet = null;

    public function setService(mixed $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getService(): GoogleSheets
    {
        if (is_null($this->service)) {
            $this->service = Google::make(service: 'sheets');
        }

        return $this->service;
    }

    /**
     * set access_token and set new service.
     */
    public function setAccessToken(array|string $token): static
    {
        Google::getCache()->clear();

        Google::setAccessToken(token: $token);

        if (isset($token['refresh_token']) && Google::isAccessTokenExpired()) {
            Google::fetchAccessTokenWithRefreshToken();
        }

        return $this->setService(Google::make(service: 'sheets'))
                    ->setDriveService(Google::make(service: 'drive'));
    }

    /**
     * @return array|null
     */
    public function getAccessToken(): ?array
    {
        return $this->getService()->getClient()->getAccessToken();
    }

    /**
     * @param  string  $spreadsheetId
     * @return $this
     */
    public function spreadsheet(string $spreadsheetId): static
    {
        $this->spreadsheetId = $spreadsheetId;

        return $this;
    }

    /**
     * @param  string  $title
     * @return $this
     */
    public function spreadsheetByTitle(string $title): static
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
    public function sheet(string $sheet): static
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @param  string  $sheetId
     * @return $this
     */
    public function sheetById(string $sheetId): static
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

        $sheets = $this->getService()->spreadsheets->get($this->getSpreadsheetId())->getSheets();

        foreach ($sheets as $sheet) {
            $list[$sheet->getProperties()->getSheetId()] = $sheet->getProperties()->getTitle();
        }

        return $list;
    }

    /**
     * @param  string  $sheetTitle
     * @return BatchUpdateSpreadsheetResponse
     */
    public function addSheet(string $sheetTitle): BatchUpdateSpreadsheetResponse
    {
        $body = new BatchUpdateSpreadsheetRequest(
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

        return $this->getService()->spreadsheets->batchUpdate($this->getSpreadsheetId(), $body);
    }

    /**
     * @param  string  $sheetTitle
     * @return BatchUpdateSpreadsheetResponse
     */
    public function deleteSheet(string $sheetTitle): BatchUpdateSpreadsheetResponse
    {
        $list = $this->sheetList();
        $id = Arr::get(array_flip($list), $sheetTitle);

        $body = new BatchUpdateSpreadsheetRequest(
            [
                'requests' => [
                    'deleteSheet' => [
                        'sheetId' => $id,
                    ],
                ],
            ]
        );

        return $this->getService()->spreadsheets->batchUpdate($this->getSpreadsheetId(), $body);
    }

    /**
     * @param  string  $property
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __get(string $property)
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
    public function __call($method, array $parameters)
    {
        if (method_exists($this->getService(), $method)) {
            return $this->getService()->{$method}(...array_values($parameters));
        }

        return $this->macroCall($method, $parameters);
    }
}
