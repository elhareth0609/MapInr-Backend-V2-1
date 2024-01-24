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
      $counters = Counter::where('place_id', $this->placeId)->get();

      $data = $counters->map(function ($counter) {
        return [
            'Counter ID' => $counter->counter_id,
            'Longitude' => $counter->longitude,
            'Latitude' => $counter->latitude,
            'Name' => $counter->name,
            'Status' => $counter->status,
            'Picture' => $counter->picture,
            'Created At' => $counter->created_at->format('Y-m-d H:i:s'),
            'Updated At' => $counter->updated_at->format('Y-m-d H:i:s'),
        ];
    });

    // Add headers to the collection
    $headers = [
        'Counter ID', 'Longitude', 'Latitude', 'Name', 'Status', 'Picture', 'Created At', 'Updated At',
    ];

    // Prepend headers to the data collection
    $collection = collect([$headers])->merge($data);

    return $collection;
    }
}
