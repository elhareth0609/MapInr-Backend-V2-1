<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Counter;

class PlacesExport implements FromCollection
{
    protected $placeId;

    public function __construct($placeId)
    {
        $this->placeId = $placeId;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      return Counter::where('place_id', $this->placeId)->get();
    }
}
