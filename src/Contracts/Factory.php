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
     * @param  Service|Sheets  $service
     * @return $this
     */
    public function setService(Service|Sheets $service): static;

    /**
     * @return Sheets
     */
    public function getService(): Sheets;

    /**
     * set access_token and set new service.
     *
     * @param  array|string  $token
     * @return $this
     */
    public function setAccessToken(array|string $token): static;

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
    public function collection(array $header, array|Collection $rows): Collection;

    /**
     * @return array
     */
    public function spreadsheetList(): array;

    /**
     * @param  Drive|Service  $drive
     * @return $this
     */
    public function setDriveService(Service|Drive $drive): static;

    /**
     * @return Drive|Service
     */
    public function getDriveService(): Service|Drive;

    /**
     * @return object
     */
    public function spreadsheetProperties(): object;

    /**
     * @return object
     */
    public function sheetProperties(): object;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @return array
     */
    public function first(): array;

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     * @return BatchUpdateValuesResponse
     */
    public function update(array $value, string $valueInputOption = 'RAW'): BatchUpdateValuesResponse;

    /**
     * @return ClearValuesResponse|null
     */
    public function clear(): ?ClearValuesResponse;

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     * @param  string  $insertDataOption
     * @return AppendValuesResponse
     */
    public function append(array $value, string $valueInputOption = 'RAW', string $insertDataOption = 'OVERWRITE'): AppendValuesResponse;

    /**
     * @return string|null
     */
    public function ranges(): ?string;

    /**
     * @param  string  $range
     * @return $this
     */
    public function range(string $range): static;

    /**
     * @param  string  $majorDimension
     * @return $this
     */
    public function majorDimension(string $majorDimension): static;

    /**
     * @param  string  $dateTimeRenderOption
     * @return $this
     */
    public function dateTimeRenderOption(string $dateTimeRenderOption): static;

    /**
     * @return string
     */
    public function getSpreadsheetId(): string;

    /**
     * @param  string  $sheetTitle
     * @return BatchUpdateSpreadsheetResponse
     */
    public function addSheet(string $sheetTitle): BatchUpdateSpreadsheetResponse;

    /**
     * @param  string  $sheetTitle
     * @return BatchUpdateSpreadsheetResponse
     */
    public function deleteSheet(string $sheetTitle): BatchUpdateSpreadsheetResponse;
}
