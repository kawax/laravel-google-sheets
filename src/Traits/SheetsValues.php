<?php

namespace Revolution\Google\Sheets\Traits;

trait SheetsValues
{
    /**
     * @var string
     */
    protected $range;

    /**
     * @var string
     */
    protected $majorDimension;

    /**
     * @var string
     */
    protected $valueRenderOption;

    /**
     * @var string
     */
    protected $dateTimeRenderOption;

    /**
     * @return array
     */
    public function all()
    {
        $query = $this->query();

        $sheets = $this->serviceValues()->batchGet($this->spreadsheetId, $query);

        $values = $sheets->getValueRanges()[0]->getValues();

        return $values ?? [];
    }

    /**
     * @return array
     */
    public function first()
    {
        $values = $this->all();

        $first = array_shift($values);

        return $first ?? [];
    }

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     *
     * @return mixed|\Google_Service_Sheets_UpdateValuesResponse
     */
    public function update(array $value, string $valueInputOption = 'RAW')
    {
        $range = $this->ranges();

        $batch = new \Google_Service_Sheets_BatchUpdateValuesRequest();
        $batch->setValueInputOption($valueInputOption);

        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues($value);
        $valueRange->setRange($range);

        $batch->setData($valueRange);

        $response = $this->serviceValues()
                         ->batchUpdate($this->spreadsheetId, $batch);

        return $response;
    }

    /**
     * @return mixed|\Google_Service_Sheets_ClearValuesResponse
     */
    public function clear()
    {
        $range = $this->ranges();

        $clear = new \Google_Service_Sheets_ClearValuesRequest();

        $response = $this->serviceValues()
                         ->clear($this->spreadsheetId, $range, $clear);

        return $response;
    }

    /**
     * @param  array  $value
     * @param  string  $valueInputOption
     * @param  string  $insertDataOption
     *
     * @return mixed|\Google_Service_Sheets_AppendValuesResponse
     */
    public function append(array $value, string $valueInputOption = 'RAW', string $insertDataOption = 'OVERWRITE')
    {
        $range = $this->ranges();

        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues($value);
        $valueRange->setRange($range);

        $optParams = [
            'valueInputOption' => $valueInputOption,
            'insertDataOption' => $insertDataOption,
        ];

        $response = $this->serviceValues()->append($this->spreadsheetId, $range, $valueRange, $optParams);

        return $response;
    }

    /**
     * @return string
     */
    public function ranges()
    {
        if (strpos($this->range, '!') === false) {
            if (empty($this->range)) {
                $ranges = $this->sheet;
            } else {
                $ranges = $this->sheet.'!'.$this->range;
            }
        } else {
            $ranges = $this->range;
        }

        return $ranges;
    }

    /**
     * @param  string  $range
     *
     * @return $this
     */
    public function range(string $range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @param  string  $majorDimension
     *
     * @return $this
     */
    public function majorDimension(string $majorDimension)
    {
        $this->majorDimension = $majorDimension;

        return $this;
    }

    /**
     * @param  string  $valueRenderOption
     *
     * @return $this
     */
    public function valueRenderOption(string $valueRenderOption)
    {
        $this->valueRenderOption = $valueRenderOption;

        return $this;
    }

    /**
     * @param  string  $dateTimeRenderOption
     *
     * @return $this
     */
    public function dateTimeRenderOption(string $dateTimeRenderOption)
    {
        $this->dateTimeRenderOption = $dateTimeRenderOption;

        return $this;
    }

    /**
     * @return \Google_Service_Sheets_Resource_SpreadsheetsValues
     */
    protected function serviceValues()
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
