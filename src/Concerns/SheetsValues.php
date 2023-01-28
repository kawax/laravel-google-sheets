<?php

namespace Revolution\Google\Sheets\Concerns;

use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\BatchUpdateValuesResponse;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\ClearValuesResponse;
use Google\Service\Sheets\Resource\SpreadsheetsValues;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait SheetsValues
{
    /**
     * @var string|null
     */
    protected ?string $range = null;

    /**
     * @var string|null
     */
    protected ?string $majorDimension = null;

    /**
     * @var string|null
     */
    protected ?string $valueRenderOption = null;

    /**
     * @var string|null
     */
    protected ?string $dateTimeRenderOption = null;

    /**
     * @return array
     */
    public function all(): array
    {
        $query = $this->query();

        $sheets = $this->serviceValues()->batchGet($this->getSpreadsheetId(), $query);

        $values = $sheets->getValueRanges()[0]->getValues();

        return $values ?? [];
    }

    /**
     * @return array
     */
    public function first(): array
    {
        $values = $this->all();

        $first = head($values);

        return $first ?: [];
    }

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     * @return BatchUpdateValuesResponse
     */
    public function update(array $value, string $valueInputOption = 'RAW'): BatchUpdateValuesResponse
    {
        $range = $this->ranges();

        $batch = new BatchUpdateValuesRequest();
        $batch->setValueInputOption($valueInputOption);

        $valueRange = new ValueRange();
        $valueRange->setValues($value);
        $valueRange->setRange($range);

        $batch->setData($valueRange);

        return $this->serviceValues()->batchUpdate($this->getSpreadsheetId(), $batch);
    }

    /**
     * @return ClearValuesResponse|null
     */
    public function clear(): ?ClearValuesResponse
    {
        $range = $this->ranges();

        $clear = new ClearValuesRequest();

        return $this->serviceValues()->clear($this->getSpreadsheetId(), $range, $clear);
    }

    /**
     * @param  array  $values
     * @param  string  $valueInputOption
     * @param  string  $insertDataOption
     * @return AppendValuesResponse
     */
    public function append(array $values, string $valueInputOption = 'RAW', string $insertDataOption = 'OVERWRITE'): AppendValuesResponse
    {
        $range = $this->ranges();
        $orderedValues = $this->orderAppendables($values);

        $valueRange = new ValueRange();
        $valueRange->setValues($orderedValues);
        $valueRange->setRange($range);

        $optParams = [
            'valueInputOption' => $valueInputOption,
            'insertDataOption' => $insertDataOption,
        ];

        return $this->serviceValues()->append($this->getSpreadsheetId(), $range, $valueRange, $optParams);
    }

    /**
     * @param  array  $values
     * @return array
     */
    public function orderAppendables(array $values): array
    {
        // The array has integer keys, so just append
        if (! Arr::isAssoc(head($values) ?: [])) {
            return $values;
        }

        // The array has keys, which we want to map to headers and order
        $header = $this->first();

        $ordered = [];
        // Gets just the values of an array that has been re-ordered to match the header order
        foreach ($values as $value) {
            array_push(
                $ordered,
                array_values(array_replace(array_flip($header), $value))
            );
        }

        // Replaces null values with empty strings to work with Google's API
        return array_map(function ($row) {
            $notNull = [];
            foreach ($row as $key => $value) {
                // If key is the same as value, that's because the user
                // didn't specify a header that exists in the sheet.
                if (is_null($value) || $key === $value) {
                    array_push($notNull, '');
                } else {
                    array_push($notNull, $value);
                }
            }

            return $notNull;
        }, $ordered);
    }

    /**
     * @return string|null
     */
    public function ranges(): ?string
    {
        // If no range is provided, we get the sheet automatically
        if (blank($this->range)) {
            return $this->sheet;
        }

        // If we only provide part of the range, we get the full proper range
        if (! Str::contains($this->range, '!')) {
            return $this->sheet.'!'.$this->range;
        }

        // If we provide the full range, it returns accurately
        return $this->range;
    }

    /**
     * @param  string  $range
     * @return $this
     */
    public function range(string $range): static
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @param  string  $majorDimension
     * @return $this
     */
    public function majorDimension(string $majorDimension): static
    {
        $this->majorDimension = $majorDimension;

        return $this;
    }

    /**
     * @param  string  $valueRenderOption
     * @return $this
     */
    public function valueRenderOption(string $valueRenderOption): static
    {
        $this->valueRenderOption = $valueRenderOption;

        return $this;
    }

    /**
     * @param  string  $dateTimeRenderOption
     * @return $this
     */
    public function dateTimeRenderOption(string $dateTimeRenderOption): static
    {
        $this->dateTimeRenderOption = $dateTimeRenderOption;

        return $this;
    }

    /**
     * @return SpreadsheetsValues
     */
    protected function serviceValues(): SpreadsheetsValues
    {
        return $this->getService()->spreadsheets_values;
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        $query = [];

        $ranges = $this->ranges();

        if (! empty($ranges)) {
            $query['ranges'] = $ranges;
        }

        if (! empty($this->majorDimension)) {
            $query['majorDimension'] = $this->majorDimension;
        }

        if (! empty($this->valueRenderOption)) {
            $query['valueRenderOption'] = $this->valueRenderOption;
        }

        if (! empty($this->dateTimeRenderOption)) {
            $query['dateTimeRenderOption'] = $this->dateTimeRenderOption;
        }

        return $query;
    }
}
