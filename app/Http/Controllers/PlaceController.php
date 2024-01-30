<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Place;
use App\Models\User;
use App\Models\Place_Worker;

class PlaceController extends Controller
{
    //
  public function place($id) {
      $place = Place::where('id', $id)->first();
      return view('dashboard.places.index')
      ->with('place', $place);
  }

  public function all_in_list(Request $request) {
    $places = Place::select('id','place_id')->get();

    return response()->json([
      'status' => 1,
      'data' => $places,
    ]);
  }


  public function all_places(Request $request) {
    $workerPlaces = Place_Worker::with(['place.counters' => function ($query) {
        $query->where('status', '1');
      }])->where('worker_id', $request->user()->id)->get();

    $workernotPlaces = Place_Worker::with(['place.counters' => function ($query) {
        $query->where('status', '0');
      }])->where('worker_id', $request->user()->id)->get();

    $isworker = $request->user() ? true : false;

    $responseData = [
        'status' => 1,
        'data' => [
            'place' => [],
        ],
    ];


    $responseData['data']['place'][] = [
      'id' => 0,
      'place_id' => 0,
      'counters' => $workernotPlaces->flatMap(function ($place_worker) {
          return $place_worker->place->counters->map(function ($counter) {
              return [
                  'id' => $counter->id,
                  'counter_id' => $counter->counter_id,
                  'name' => $counter->name,
                  'latitude' => $counter->latitude,
                  'longitude' => $counter->longitude,
              ];
          });
      })->all(),
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
                  'name' => $counter->name,
                  'latitude' => $counter->latitude,
                  'longitude' => $counter->longitude,
              ];
          })->all(),
      ];
    }

      // foreach ($workernotPlaces as $workernotPlace) {
    //     // Add place details to the response
    // $responseData['data']['place']['notcounter'][] = [
    //     'id' => $workernotPlace->place->id,
    //     'place_id' => $workernotPlace->place->place_id,
    //     'counters' => $workernotPlace->place->counters->map(function ($counter) {
    //         return [
    //             'id' => $counter->id,
    //             'counter_id' => $counter->counter_id,
    //             'name' => $counter->name,
    //             'latitude' => $counter->latitude,
    //             'longitude' => $counter->longitude,
    //         ];
    //     })->all(),
    // ];
    // }

    // foreach ($workerPlaces as $workerPlace) {
    //   // Add place details to the response
    //   $responseData['data']['place']['counters'][] = [
    //       'id' => $workerPlace->place->id,
    //       'place_id' => $workerPlace->place->place_id,
    //       'counters' => $workerPlace->place->counters->map(function ($counter) {
    //           return [
    //               'id' => $counter->id,
    //               'counter_id' => $counter->counter_id,
    //               'name' => $counter->name,
    //               'latitude' => $counter->latitude,
    //               'longitude' => $counter->longitude,
    //           ];
    //       })->all(),
    //   ];
    // }
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
