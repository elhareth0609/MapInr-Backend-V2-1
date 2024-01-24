<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Facades\Excel;

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

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $this->beforeWriting($event->getWriter());
            },
        ];
    }

    private function beforeWriting($excel)
    {
        $activeSheet = $excel->getActiveSheet();

        // Get the starting row for embedding images
        $imageRow = 2;

        foreach ($this->collection() as $rowIndex => $rowData) {
            // Assuming $rowData['Picture'] contains the image file name or path
            $imagePath = public_path("images/{$rowData['Picture']}");

            if (file_exists($imagePath)) {
                $imageData = file_get_contents($imagePath);
                $base64 = 'data:image/jpeg;base64,' . base64_encode($imageData);

                // Embed the image in the Excel file
                $drawing = new Drawing();
                $drawing->setPath($imagePath);
                $drawing->setCoordinates("G{$imageRow}");
                $drawing->setWidth(100);
                $drawing->setHeight(100);
                $drawing->setWorksheet($activeSheet);

                // Increment the row index for the next image
                $imageRow++;
            }
        }
    }
}
