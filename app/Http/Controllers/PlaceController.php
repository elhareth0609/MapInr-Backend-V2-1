<?php

namespace App\Http\Controllers;

use App\Models\Counter;

use App\Models\Place;
use App\Models\Place_Worker;
use App\Models\User;
use App\Models\Worker_Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{
  public function place($id)
  {
    $place = Place::where('id', $id)->first();
    return view('dashboard.places.index')->with('place', $place);
  }

  public function all_places(Request $request)
  {

    $responseData = [
      'status' => 1,
      'data' => [
        'place' => [],
      ],
    ];

    // shared
    $sharedCounters = Counter::has('shared')->with('shared')->get();

    $responseData['data']['place'][] = [
      'id' => 999999999999999999,
      'place_id' => 999999999999999999,
      'counters' => $sharedCounters->map(function ($counter) {
        return [
          'id' => $counter->id,
          'counter_id' => $counter->counter_id,
          'place_id' => 999999999999999999,
          'phone' => $counter->phone,
          'name' => $counter->name,
          'latitude' => $counter->latitude,
          'longitude' => $counter->longitude,
        ];
      }),
    ];

    // workerPalces
    $workerPlaces = Place_Worker::with([
      'place.counters' => function ($query) use ($request) {
        $query->where('worker_id', $request->user()->id)
              ->orWhere('status', '1');
      },
    ])
    ->where('worker_id', $request->user()->id)
    ->get()
    ->sortBy('place_id');

      foreach ($workerPlaces as $workerPlace) {
      //   $workerPlaceCounter = $workerPlace->place->counters->filter(function ($counter) {
      //     return !$counter->shared()->exists();
      // });
      $responseData['data']['place'][] = [
        'id' => $workerPlace->place->id,
        'place_id' => $workerPlace->place->place_id,
        'counters' => $workerPlace->place->counters
        ->reject(function ($counter) {
          return $counter->shared()->exists();
        })
          ->map(function ($counter) {
            return [
              'id' => $counter->id,
              'counter_id' => $counter->counter_id,
              'place_id' => $counter->place_id,
              'phone' => $counter->phone,
              'name' => $counter->name,
              'latitude' => $counter->latitude,
              'longitude' => $counter->longitude,
            ];
          }),
      ];
    }

    // workerCounters
    $workerCounters = Worker_Counter::where('worker_id', $request->user()->id)->pluck('counter_id');

    $placeIdsFirst = Counter::whereIn('id', $workerCounters)
      ->pluck('place_id')
      ->unique();

    $placeIds = Place::whereIn('id', $placeIdsFirst)->pluck('place_id')->unique();

    $responseData['data']['place'] = collect($responseData['data']['place']);

    foreach ($placeIds as $placeId) {
        if (!$responseData['data']['place']->contains('place_id', $placeId)) {
            $place = Place::where('place_id',$placeId)->first();

            if ($place) {
                $counters = Counter::where('place_id', $place->id)
                ->whereIn('id', function ($query) use ($request) {
                    $query->select('counter_id')
                        ->from('worker__counters')
                        ->where('worker_id', $request->user()->id);
                })
                ->get();

                $counters = $counters->filter(function ($counter) {
                  return !$counter->shared()->exists();
                });

                $responseData['data']['place']->push([
                    'id' => $place->id,
                    'place_id' => $place->place_id,
                    'counters' => $counters->map(function ($counter) {
                        return [
                            'id' => $counter->id,
                            'counter_id' => $counter->counter_id,
                            'place_id' => $counter->place_id,
                            'phone' => $counter->phone,
                            'name' => $counter->name,
                            'latitude' => $counter->latitude,
                            'longitude' => $counter->longitude,
                        ];
                    }),
                ]);
            }
        }
    }



    return response()->json($responseData);
  }

  public function place_workers($id, Request $request) {
    $place = Place::where('id', $id)->first();
    $workers = Place_Worker::where('place_id', $id)->pluck('worker_id');
    $users = User::where('role','!=','admin')->pluck('id', 'fullname');

    return view('dashboard.places.workers')
      ->with('place', $place)
      ->with('workers', $workers)
      ->with('users', $users);
  }

  public function place_counters($id, Request $request) {
    $place = Place::where('id', $id)->first();

    return view('dashboard.places.counters')->with('place', $place);
  }

  public function destroy(Request $request,$id) {

    $validator = Validator::make($request->all(), [
      'password' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => __('Validation failed'),
            'errors' => $validator->errors()->first(),
        ], 422);
    }

    try {

        $place = Place::find($id);
        if ($place) {
          $place->delete();

          return response()->json([
            'state' => __('Success'),
            'message' => __('Place deleted successfully.'),
          ]);
        } else {
          return response()->json([
              'status' => __('Success'),
              'message' => __('Sorry,'),
              'errors' => __('There Is No Place To Deleted.'),
            ],401);
        }
      } catch (\Exception $e) {
        return response()->json([
            'status' => 1,
            'message' => __('Error'),
            'errors' => $e->getMessage()
        ], 422);
      }

  }
}
