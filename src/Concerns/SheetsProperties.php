<?php

namespace Revolution\Google\Sheets\Concerns;

use stdClass;

trait SheetsProperties
{
    public function spreadsheetProperties(): object
    {
        return $this->getService()
            ->spreadsheets
            ->get($this->getSpreadsheetId())
            ->getProperties()
            ->toSimpleObject();
    }

    public function sheetProperties(): object
    {
        $sheets = $this->getService()
            ->spreadsheets
            ->get($this->getSpreadsheetId(), ['ranges' => $this->sheet])
            ->getSheets();

        return $sheets[0]->getProperties()->toSimpleObject();
    }

    public function getSpreadsheetId(): string
    {
        return $this->spreadsheetId ?? '';
    }
}
