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
  public function place($id) {
    $place = Place::where('id', $id)->first();
    return view('dashboard.places.index')->with('place', $place);
  }

  public function all_places(Request $request) {

    $responseData = [
      'status' => 1,
      'data' => [
        'place' => [],
      ],
    ];

    // shared
    $sharedCounters = Counter::has('shared')
    ->with('shared')
    ->whereHas('shared', function ($query) use ($request) {
        $query->where('user_id', $request->user()->id);
    })
    ->get();

    $responseData['data']['place'][] = [
      'id' => 999999999999999999,
      'place_id' => 999999999999999999,
      'counters' => $sharedCounters->map(function ($counter) {
        return [
          'id' => $counter->id,
          'counter_id' => $counter->counter_id,
          'place_id' => 0,
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
      $responseData['data']['place'][] = [
        'id' => $workerPlace->place->id,
        'place_id' => $workerPlace->place->place_id,
        'counters' => $workerPlace->place->counters
        ->map(function ($counter) {
          if (!$counter->shared()->exists()) {
            return [
              'id' => $counter->id,
              'counter_id' => $counter->counter_id,
              'place_id' => $counter->place_id,
              'phone' => $counter->phone1? $counter->phone1->phone : $counter->phone,
              'name' => $counter->name,
              'latitude' => $counter->latitude,
              'longitude' => $counter->longitude,
            ];
          } else {
            unset($counter);
          }
        })
        ->reject(function ($value) {
          return is_null($value);
        })
        ->values(), // Reset keys

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

                // $counters = $counters->filter(function ($counter) {
                //   return !$counter->shared()->exists();
                // });

                $responseData['data']['place']->push([
                    'id' => $place->id,
                    'place_id' => $place->place_id,
                    'counters' => $counters->map(function ($counter) {
                      if (!$counter->shared()->exists()) {
                        return [
                            'id' => $counter->id,
                            'counter_id' => $counter->counter_id,
                            'place_id' => $counter->place_id,
                            'phone' => $counter->phone,
                            'name' => $counter->name,
                            'latitude' => $counter->latitude,
                            'longitude' => $counter->longitude,
                        ];
                      } else {
                        unset($counter);
                      }
                    })
                    ->reject(function ($value) {
                      return is_null($value);
                    })
                    ->values(), // Reset keys

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

  public function place_copied($id, Request $request) {
    $place = Place::where('id', $id)->first();

    return view('dashboard.places.copied')->with('place', $place);
  }

  public function removeCounterPlace($id, $counterId) {

    if ($counterId == 0 && $id == 0) {
      return response()->json([
          'status' => 0,
          'message' => __('Validation failed'),
          'error' => __("Place Id Should Be Not 0."),
      ], 422);
    }

    try {
      $counter = Counter::find($counterId);
      if ($counter) {
        $counter->delete();
      }

      return response()->json([
        'state' => __("Success"),
        'message' => __("Remove Counter From Place Successfully."),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ], 500);
    }

  }

  public function destroy(Request $request,$id) {

    $validator = Validator::make($request->all(), [
      'password' => 'required|string|in:10',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => __('Validation failed'),
            'errors' => $validator->errors()->first(),
        ], 422);
    }

    if ($id == 0) {
      return response()->json([
          'status' => 0,
          'message' => __('Validation failed'),
          'error' => __("Place Id Should Be Not 0."),
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
