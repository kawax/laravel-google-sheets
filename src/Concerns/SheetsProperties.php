<?php

namespace Revolution\Google\Sheets\Concerns;

use stdClass;

trait SheetsProperties
{
    /**
     * @return stdClass
     */
    public function spreadsheetProperties(): stdClass
    {
        return $this->getService()
            ->spreadsheets
            ->get($this->getSpreadsheetId())
            ->getProperties()
            ->toSimpleObject();
    }

    /**
     * @return stdClass
     */
    public function sheetProperties(): stdClass
    {
        $sheets = $this->getService()
            ->spreadsheets
            ->get($this->getSpreadsheetId(), ['ranges' => $this->sheet])
            ->getSheets();

        return $sheets[0]->getProperties()->toSimpleObject();
    }

    /**
     * @return string
     */
    public function getSpreadsheetId(): string
    {
        return $this->spreadsheetId ?? '';
    }
}
