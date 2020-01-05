<?php

namespace Revolution\Google\Sheets\Concerns;

use Illuminate\Support\Collection;

trait SheetsCollection
{
    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $values = $this->all();

        return Collection::make($values);
    }

    /**
     * @param  array  $header
     * @param  array|Collection  $rows
     *
     * @return Collection
     */
    public function collection(array $header, $rows): Collection
    {
        return Collection::make($rows)->map(function ($item) use ($header) {
            $row = Collection::make($item)->pad(count($header), '');

            return Collection::make($header)->combine($row);
        });
    }
}
