<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminUserRowsImport implements ToCollection, WithHeadingRow
{
    private $rows;

    public function collection(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function rows(): Collection
    {
        return $this->rows ?: collect();
    }
}
