<?php

namespace Revolution\Google\Sheets\Concerns;

use Illuminate\Support\Collection;

trait SheetsCollection
{
    public function get(): Collection
    {
        $values = $this->all();

        return Collection::make($values);
    }

    public function collection(array $header, array|Collection $rows): Collection
    {
        return Collection::make($rows)->map(function ($item) use ($header) {
            $row = Collection::make($item)->pad(count($header), '');

            return Collection::make($header)->combine($row);
        });
    }
}
