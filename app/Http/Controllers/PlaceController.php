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
    $workerPlaces->place->counters;

    $data = Place::select('id', 'place_id', 'longitude', 'latitude')
        ->with([
            'counters' => function ($query) {
                $query->select('id', 'place_id', 'counter_id', 'name', 'longitude', 'latitude')
                    ->where('status', 1);
            }
        ])
        ->get();

        return response()->json([
      'status' => 1,
      'message' => $workerPlaces
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
