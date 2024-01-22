<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Counter;
use App\Models\Place;

class YourExcelImport implements ToCollection
{
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function collection(Collection $rows)
    {
      // Extract place_id from the file name
      $fileNameWithoutExtension = pathinfo($this->fileName, PATHINFO_FILENAME);
      $placeId = (int)$fileNameWithoutExtension; // Assuming place_id is an integer

      $search = Place::where('place_id',$placeId)->first()->delete();

      $place = new Place;
      $place->place_id = $placeId;
      $place->longitude = 23.0;
      $place->latitude = 34.0;
      // $place->name = 'counter_' . $row[0];
      $place->save();

      $rows->skip(1)->each(function ($row) use($place){
          $counter = new Counter;
          $counter->counter_id = $row[0];
          $counter->place_id = $place->id;
          $counter->longitude = $row[1];
          $counter->latitude = $row[2];
          $counter->name = 'counter_' . $row[0];
          $counter->status = 1;
          $counter->save();
        });
      }
}
