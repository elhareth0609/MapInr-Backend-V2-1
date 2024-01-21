<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Counter;

class YourExcelImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Counter::create([
                'counter_id' => $row[0], // Assuming 'r' is in the first column
                'longitude' => $row[1], // Assuming 'x' is in the second column
                'latitude'  => $row[2], // Assuming 'y' is in the third column
                'name'      => 'counter_' . $row[0],
                'status'    => 1,
            ]);
        }
    }
}
