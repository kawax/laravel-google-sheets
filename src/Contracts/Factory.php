<?php

namespace Revolution\Google\Sheets\Contracts;

use Google_Service_Drive;
use Google_Service_Sheets;
use Illuminate\Support\Collection;

interface Factory
{
    /**
     * @param  Google_Service_Sheets|\Google_Service  $service
     */
    public function setService($service);

    /**
     * @return Google_Service_Sheets
     */
    public function getService(): Google_Service_Sheets;

    /**
     * set access_token and set new service.
     *
     * @param  string|array  $token
     *
     * @return $this
     */
    public function setAccessToken($token);

    /**
     * @param  string  $spreadsheetId
     *
     * @return $this
     */
    public function spreadsheet(string $spreadsheetId);

    /**
     * @param  string  $title
     *
     * @return $this
     */
    public function spreadsheetByTitle(string $title);

    /**
     * @param  string  $sheet
     *
     * @return $this
     */
    public function sheet(string $sheet);

    /**
     * @param  string  $sheetId
     *
     * @return $this
     */
    public function sheetById(string $sheetId);

    /**
     * @return array
     */
    public function sheetList(): array;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection;

    /**
     * @param  array  $header
     * @param  array|\Illuminate\Support\Collection  $rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection(array $header, $rows): Collection;

    /**
     * @return array
     */
    public function spreadsheetList(): array;

    /**
     * @param  Google_Service_Drive|\Google_Service  $drive
     */
    public function setDriveService($drive);

    /**
     * @return Google_Service_Drive|\Google_Service
     */
    public function getDriveService();

    /**
     * @return \stdClass
     */
    public function spreadsheetProperties();

    /**
     * @return \stdClass
     */
    public function sheetProperties();

    /**
     * @return array
     */
    public function all();

    /**
     * @return array
     */
    public function first();

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     *
     * @return mixed|\Google_Service_Sheets_UpdateValuesResponse
     */
    public function update(array $value, string $valueInputOption = 'RAW');

    /**
     * @return mixed|\Google_Service_Sheets_ClearValuesResponse
     */
    public function clear();

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     * @param  string  $insertDataOption
     *
     * @return mixed|\Google_Service_Sheets_AppendValuesResponse
     */
    public function append(array $value, string $valueInputOption = 'RAW', string $insertDataOption = 'OVERWRITE');

    /**
     * @return string
     */
    public function ranges();

    /**
     * @param  string  $range
     *
     * @return $this
     */
    public function range(string $range);

    /**
     * @param  string  $majorDimension
     *
     * @return $this
     */
    public function majorDimension(string $majorDimension);

    /**
     * @param  string  $dateTimeRenderOption
     *
     * @return $this
     */
    public function dateTimeRenderOption(string $dateTimeRenderOption);

    /**
     * @return string
     */
    public function getSpreadsheetId();

    /**
     * @param  string  $sheetTitle
     *
     * @return \Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function addSheet(string $sheetTitle);

    /**
     * @param  string  $sheetTitle
     *
     * @return \Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function deleteSheet(string $sheetTitle);
}
