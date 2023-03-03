<?php

namespace Revolution\Google\Sheets\Contracts;

use Google\Service\Sheets;
use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets\BatchUpdateSpreadsheetResponse;
use Google\Service\Sheets\BatchUpdateValuesResponse;
use Google\Service\Sheets\ClearValuesResponse;
use Illuminate\Support\Collection;

interface Factory
{
    public function setService(mixed $service): static;

    public function getService(): Sheets;

    /**
     * set access_token and set new service.
     */
    public function setAccessToken(array|string $token): static;

    public function spreadsheet(string $spreadsheetId): static;

    public function spreadsheetByTitle(string $title): static;

    public function sheet(string $sheet): static;

    public function sheetById(string $sheetId): static;

    public function sheetList(): array;

    public function get(): Collection;

    public function collection(array $header, array|Collection $rows): Collection;

    /**
     * @return array
     */
    public function spreadsheetList(): array;

    public function setDriveService(mixed $drive): static;

    public function getDriveService(): mixed;

    public function spreadsheetProperties(): object;

    public function sheetProperties(): object;

    public function all(): array;

    public function first(): array;

    public function update(array $value, string $valueInputOption = 'RAW'): BatchUpdateValuesResponse;

    public function clear(): ?ClearValuesResponse;

    public function append(
        array $value,
        string $valueInputOption = 'RAW',
        string $insertDataOption = 'OVERWRITE',
    ): AppendValuesResponse;

    public function ranges(): ?string;

    public function range(string $range): static;

    public function majorDimension(string $majorDimension): static;

    public function dateTimeRenderOption(string $dateTimeRenderOption): static;

    public function getSpreadsheetId(): string;

    public function addSheet(string $sheetTitle): BatchUpdateSpreadsheetResponse;

    public function deleteSheet(string $sheetTitle): BatchUpdateSpreadsheetResponse;
}
