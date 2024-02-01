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
                'Counter Number' => $counter->counter_id,
                'Longitude' => $counter->longitude,
                'Latitude' => $counter->latitude,
                'Name' => $counter->name,
                'Created At' => $counter->created_at->format('Y-m-d H:i:s'),
            ];
        });

        // Add headers to the collection
        $headers = [
            'Counter Number', 'Longitude', 'Latitude', 'Name', 'Created At',
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

    public function insertPictures($sheet)
    {
        $counters = Counter::where('place_id', $this->placeId)->get();

        // Get the starting row for embedding images
        $imageRow = 2;

        foreach ($counters as $counter) {
            // Check if the picture exists
            if (!empty($counter->picture)) {
                $storagePath = 'public/assets/img/counters/' . $counter->picture;
                $imageUrl = Storage::url($storagePath);

                if ($imageUrl) {
                    $tempImagePath = storage_path("app/{$storagePath}");

                    // Embed the image in the Excel file
                    $drawing = new Drawing();
                    $drawing->setPath($tempImagePath);
                    $drawing->setWidth(20);
                    $drawing->setHeight(17);

                    // Get the underlying PhpSpreadsheet Worksheet object
                    $worksheet = $sheet->getDelegate();

                    // Set the Worksheet for the Drawing
                    $drawing->setWorksheet($worksheet);

                    // Set the coordinates for the image
                    $drawing->setCoordinates("G{$imageRow}");

                    // Increment the row index for the next image
                    $imageRow++;
                }
            }
        }
    }

  }
