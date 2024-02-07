<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Counter;
use App\Models\Place;

class YourExcelImport implements ToCollection
{
  protected $fileName;
  protected $id;

    public function __construct($fileName,$id)
    {
      $this->fileName = $fileName;
      $this->id = $id;
    }

    public function collection(Collection $rows)
    {
      // Extract place_id from the file name
      $fileNameWithoutExtension = pathinfo($this->fileName, PATHINFO_FILENAME);
      $placeId = (int)$fileNameWithoutExtension; // Assuming place_id is an integer

      $search = Place::where('place_id',$placeId)->first();
      if ($search) {
        $search->delete();
      }

      $place = new Place;
      $place->place_id = $placeId;
      $place->municipality_id = $this->id;
      $place->place_name = 'place_' . $placeId;
      $place->save();

      $rows->skip(1)->each(function ($row) use($place){
          $counter = new Counter;
          $counter->counter_id = $row[0];
          $counter->place_id = $place->id;
          $counter->latitude = $row[1];
          $counter->longitude = $row[2];
          $counter->name = 'counter_' . $row[0];
          $counter->status = 1;
          $counter->save();
        });
      }
}
