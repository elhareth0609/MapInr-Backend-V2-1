<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Place;
use App\Models\Place_Worker;

class PlaceController extends Controller
{
    //
  public function place($id) {
      $place = Place::where('id', $id)->first();
      return view('dashboard.places.index')
      ->with('place', $place);
  }

  public function all_places(Request $request) {
    $workerPlaces = Place_Worker::where('worker_id', $request->user()->id)->get();
    $isworker = $request->user() ? true : false;
    // Initialize the response structure
    $responseData = [
        'status' => 1,
        'message' => [
            'is_worker' => $isworker, // Check if the workerPlaces collection is not empty
            'place' => [],
        ],
    ];

    foreach ($workerPlaces as $workerPlace) {
        // Add place details to the response
    $responseData['message']['place'][] = [
        'id' => $workerPlace->place->id,
        'place_id' => $workerPlace->place->place_id,
        'latitude' => $workerPlace->place->latitude,
        'longitude' => $workerPlace->place->longitude,
        'counters' => $workerPlace->place->counters->map(function ($counter) {
            return [
                'id' => $counter->id,
                'counter_id' => $counter->counter_id,
                'name' => $counter->name,
                'latitude' => $counter->latitude,
                'longitude' => $counter->longitude,
            ];
        })->all(),
    ];
    }
        return response()->json([
      'status' => 1,
      'data' => $responseData
    ]);
  }

  public function place_workers($id,Request $request) {
    $place = Place::where('id', $id)->first();
    return view('dashboard.places.workers')
    ->with('place', $place);
  }

  public function place_counters($id,Request $request) {
    $place = Place::where('id', $id)->first();
    return view('dashboard.places.counters')
    ->with('place', $place);
  }
}
