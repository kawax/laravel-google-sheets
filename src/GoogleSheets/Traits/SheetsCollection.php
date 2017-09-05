<?php
namespace Revolution\Google\Sheets\Traits;

use Illuminate\Support\Collection;

trait SheetsCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
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
    public function collection(array $header, $rows): Collection
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
