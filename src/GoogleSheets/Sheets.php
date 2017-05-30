<?php

namespace GoogleSheets;

use \Google_Service_Sheets;

class Sheets
{
    use Traits\SheetsValues;
    use Traits\SheetsDrive;
    use Traits\SheetsProperties;
    use Traits\SheetsCollection;

    /**
     * @var \Google_Service_Sheets
     */
    protected $service;

    /**
     * @var string
     */
    protected $spreadsheetId;

    /**
     * @var string
     */
    protected $sheet;

    /**
     * @param Google_Service_Sheets|\Google_Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return Google_Service_Sheets
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $spreadsheetId
     *
     * @return $this
     */
    public function spreadsheet($spreadsheetId)
    {
        $this->spreadsheetId = $spreadsheetId;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function spreadsheetByTitle($title)
    {
        $list = $this->spreadsheetList();
        $id = array_get(array_flip($list), $title);

        $this->spreadsheetId = $id;

        return $this;
    }

    /**
     * @param string $sheet
     *
     * @return $this
     */
    public function sheet($sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @param string $sheetId
     *
     * @return $this
     */
    public function sheetById($sheetId)
    {
        $list = $this->sheetList();

        $sheet = array_get($list, $sheetId);

        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @return array
     */
    public function sheetList()
    {
        $list = [];

        $sheets = $this->service->spreadsheets->get($this->spreadsheetId)->getSheets();

        foreach ($sheets as $sheet) {
            $list[$sheet->getProperties()->getSheetId()] = $sheet->getProperties()->getTitle();
        }

        return $list;
    }

    /**
     * @param string $property
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __get($property)
    {
        if (property_exists($this->service, $property)) {
            return $this->service->{$property};
        }

        throw new \InvalidArgumentException(sprintf('Property [%s] does not exist.', $property));
    }

    /**
     * Magic call method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->service, $method)) {
            return call_user_func_array([$this->service, $method], $parameters);
        }

        throw new \BadMethodCallException(sprintf('Method [%s] does not exist.', $method));
    }
}
