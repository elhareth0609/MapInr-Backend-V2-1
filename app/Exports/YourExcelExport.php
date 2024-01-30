<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Counter;

class YourExcelExport implements FromCollection, WithEvents
{
    protected $placeId;

    public function __construct($id)
    {
        $this->placeId = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $counters = Counter::where('place_id', $this->placeId)->get();

        // Transform counters into a collection
        $data = $counters->map(function ($counter) {
            return [
                'Counter ID' => $counter->counter_id,
                'Longitude' => $counter->longitude,
                'Latitude' => $counter->latitude,
                'Name' => $counter->name,
                'Status' => $counter->status,
                'Created At' => $counter->created_at->format('Y-m-d H:i:s'),
                'Updated At' => $counter->updated_at->format('Y-m-d H:i:s'),
                'Picture' => $counter->picture, // Use the "picture" field to access the storage name of the photo
            ];
        });

        // Add headers to the collection
        $headers = [
            'Counter ID', 'Longitude', 'Latitude', 'Name', 'Status', 'Created At', 'Updated At', 'Picture',
        ];

        // Prepend headers to the data collection
        $collection = collect([$headers])->merge($data);

        return $collection;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->insertPictures($event->sheet);
            },
        ];
    }

    private function insertPictures($sheet)
    {
        $counters = Counter::where('place_id', $this->placeId)->get();

        // Get the starting row for embedding images
        $imageRow = 2;

        foreach ($counters as $counter) {
          if($counter->$counter) {
            $storagePath = 'public/assets/img/counters/' . $counter->picture;
            $imageUrl = Storage::url($storagePath);

            if ($imageUrl) {
                $tempImagePath = public_path("storage/{$storagePath}");

                // Embed the image in the Excel file
                $drawing = new Drawing();
                $drawing->setPath($tempImagePath);
                $drawing->setCoordinates("G{$imageRow}");
                $drawing->setWidth(100);
                $drawing->setHeight(100);
                $drawing->setWorksheet($sheet);

                // Increment the row index for the next image
                $imageRow++;
            }
          }
        }
    }
}
