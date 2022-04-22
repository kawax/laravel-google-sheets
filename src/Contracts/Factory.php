<?php

namespace Revolution\Google\Sheets\Contracts;

use Google\Service;
use Google\Service\Drive;
use Google\Service\Sheets;
use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets\BatchUpdateSpreadsheetResponse;
use Google\Service\Sheets\BatchUpdateValuesResponse;
use Google\Service\Sheets\ClearValuesResponse;
use Illuminate\Support\Collection;

interface Factory
{
    /**
     * @param  Sheets|Service  $service
     * @return $this
     */
    public function setService($service): static;

    /**
     * @return Sheets
     */
    public function getService(): Sheets;

    /**
     * set access_token and set new service.
     *
     * @param  string|array  $token
     * @return $this
     */
    public function setAccessToken($token): static;

    /**
     * @param  string  $spreadsheetId
     * @return $this
     */
    public function spreadsheet(string $spreadsheetId): static;

    /**
     * @param  string  $title
     * @return $this
     */
    public function spreadsheetByTitle(string $title): static;

    /**
     * @param  string  $sheet
     * @return $this
     */
    public function sheet(string $sheet): static;

    /**
     * @param  string  $sheetId
     * @return $this
     */
    public function sheetById(string $sheetId): static;

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
     * @return \Illuminate\Support\Collection
     */
    public function collection(array $header, $rows): Collection;

    /**
     * @return array
     */
    public function spreadsheetList(): array;

    /**
     * @param  Drive|Service  $drive
     * @return $this
     */
    public function setDriveService($drive);

    /**
     * @return Drive|Service
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
     * @return BatchUpdateValuesResponse
     */
    public function update(array $value, string $valueInputOption = 'RAW');

    /**
     * @return ClearValuesResponse
     */
    public function clear();

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     * @param  string  $insertDataOption
     * @return AppendValuesResponse
     */
    public function append(array $value, string $valueInputOption = 'RAW', string $insertDataOption = 'OVERWRITE');

    /**
     * @return string
     */
    public function ranges();

    /**
     * @param  string  $range
     * @return $this
     */
    public function range(string $range);

    /**
     * @param  string  $majorDimension
     * @return $this
     */
    public function majorDimension(string $majorDimension);

    /**
     * @param  string  $dateTimeRenderOption
     * @return $this
     */
    public function dateTimeRenderOption(string $dateTimeRenderOption);

    /**
     * @return string
     */
    public function getSpreadsheetId();

    /**
     * @param  string  $sheetTitle
     * @return BatchUpdateSpreadsheetResponse
     */
    public function addSheet(string $sheetTitle);

    /**
     * @param  string  $sheetTitle
     * @return BatchUpdateSpreadsheetResponse
     */
    public function deleteSheet(string $sheetTitle);
}
