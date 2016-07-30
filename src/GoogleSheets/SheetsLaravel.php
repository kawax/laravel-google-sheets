<?php
namespace GoogleSheets;

use Illuminate\Support\Collection;

class SheetsLaravel extends Sheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        $values = $this->all();

        return collect($values);
    }

    /**
     * @param array $header
     * @param array|\Illuminate\Support\Collection $rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection($header, $rows)
    {
        $collection = [];

        if ($rows instanceof Collection) {
            $rows = $rows->toArray();
        }

        foreach ($rows as $row) {
            $col = [];

            foreach ($header as $index => $head) {
                $col[$head] = empty($row[$index]) ? '' : $row[$index];
            }

            if (!empty($col)) {
                $collection[] = $col;
            }
        }

        return collect($collection);
    }
}
