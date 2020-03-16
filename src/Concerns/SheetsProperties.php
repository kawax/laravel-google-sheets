<?php

namespace Revolution\Google\Sheets\Concerns;

trait SheetsProperties
{
    /**
     * @return \stdClass
     */
    public function spreadsheetProperties()
    {
        return $this->serviceSpreadsheets()
            ->get($this->spreadsheetId)
            ->getProperties()
            ->toSimpleObject();
    }

    /**
     * @return \stdClass
     */
    public function sheetProperties()
    {
        $sheets = $this->serviceSpreadsheets()
            ->get($this->spreadsheetId, ['ranges' => $this->sheet])
            ->getSheets();

        return $sheets[0]->getProperties()->toSimpleObject();
    }

    /**
     * @return string
     */
    public function getSpreadsheetId()
    {
        return $this->spreadsheetId;
    }
}
