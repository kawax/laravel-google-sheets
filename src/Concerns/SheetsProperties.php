<?php

namespace Revolution\Google\Sheets\Concerns;

use stdClass;

trait SheetsProperties
{
    /**
     * @return object
     */
    public function spreadsheetProperties(): object
    {
        return $this->getService()
            ->spreadsheets
            ->get($this->getSpreadsheetId())
            ->getProperties()
            ->toSimpleObject();
    }

    /**
     * @return object
     */
    public function sheetProperties(): object
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
