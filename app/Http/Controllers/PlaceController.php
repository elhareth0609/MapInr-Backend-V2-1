<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Place;
use App\Models\User;
use App\Models\Place_Worker;

class PlaceController extends Controller
{

  public function place($id) {
      $place = Place::where('id', $id)->first();
      return view('dashboard.places.index')
      ->with('place', $place);
  }

  public function all_places(Request $request) {
    $workerPlaces = Place_Worker::with(['place.counters' => function ($query) use ($request) {
      $query->where('worker_id', $request->user()->id)
            ->orWhere('status', '1');
    }])
    ->where('worker_id', $request->user()->id)
    ->get()
    ->sortBy('place_id');


    $responseData = [
        'status' => 1,
        'data' => [
            'place' => [],
        ],
    ];

    foreach ($workerPlaces as $workerPlace) {
      // Add place details to the response
      $responseData['data']['place'][] = [
          'id' => $workerPlace->place->id,
          'place_id' => $workerPlace->place->place_id,
          'counters' => $workerPlace->place->counters->map(function ($counter) {
              return [
                  'id' => $counter->id,
                  'counter_id' => $counter->counter_id,
                  'place_id' => $counter->place_id,
                  'name' => $counter->name,
                  'latitude' => $counter->latitude,
                  'longitude' => $counter->longitude,
              ];
          })->all(),
      ];
    }

    return response()->json($responseData);
  }

  public function place_workers($id,Request $request) {
    $place = Place::where('id', $id)->first();
    $workers = Place_Worker::where('place_id', $id)->pluck('worker_id');
    $users = User::pluck('id', 'fullname');

    return view('dashboard.places.workers')
    ->with('place', $place)
    ->with('workers', $workers)
    ->with('users', $users);
  }

  public function place_counters($id,Request $request) {
    $place = Place::where('id', $id)->first();
    return view('dashboard.places.counters')
    ->with('place', $place);
  }
}
