<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Place;
use App\Models\Place_Worker;
use App\Models\Worker_Counter;


class UserController extends Controller
{
  //
  public function user($id) {
    $user = User::find($id);
    return view('dashboard.users.index')
    ->with('user',$user);
  }

  public function generate_password() {
      // $generatedPassword = Str::random(6, '0123456789');
      $generatedPassword = '';
      for ($i = 0; $i < 8; $i++) {
          $generatedPassword .= mt_rand(0, 9);
      }


      $passwordExists = User::where('password', Hash::make($generatedPassword))->exists();

      while ($passwordExists) {
          $generatedPassword = Str::random(12);
          $passwordExists = User::where('password', Hash::make($generatedPassword))->exists();
      }

      return response()->json([
        'password' => $generatedPassword
      ]);
  }

  public function create(Request $request) {

    $rules = [
      'firstname' => 'required|string',
      'lastname' => 'required|string',
      'email' => 'sometimes|email|nullable|unique:users',
      'phone' => 'sometimes|nullable|string',
      'password' => 'required|string|min:8',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => __('Validation failed'),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    $user = new User();
    $user->fullname = $request->firstname . ' ' . $request->lastname;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = $request->password;
    $user->save();

    $place = new Place_Worker;
    $place->place_id = 0;
    $place->worker_id = $user->id;
    $place->save();

    return response()->json([
      'state' => __('Success'),
      'message' => __('User created successfully'),
    ]);
  }

  public function destroy($id) {
    $user = User::find($id);
    if ($user) {
        $user->delete();

        return response()->json([
          'state' => __('Success'),
          'message' => __('User deleted successfully.'),
        ]);
    } else {
        return response()->json([
          'status' => __('Success'),
          'message' => __('Sorry,'),
          'error' => __('There Is No User To Deleted.'),
        ],401);
    }
  }

  public function addPlaceWorker(Request $request) {

    try {
      Place_Worker::where('place_id', $request->place_id)->delete();

      $workerIds = $request->input('selectedWorkers',[]);
      $placeId = $request->input('place_id');

      foreach ($workerIds as $workerId) {
          $placeWorker = new Place_Worker;
          $placeWorker->worker_id = $workerId;
          $placeWorker->place_id = $placeId;
          $placeWorker->save();
      }

      return response()->json([
        'state' => __("Success"),
        'message' => __("Add Worker To Place Is Successful."),
      ], 200);


  } catch (\Exception $e) {
    return response()->json([
      'status' => 0,
      'error' => $e->getMessage(),
    ], 500); // Assuming 500 is the appropriate HTTP status code for a server error

  }
  }

  public function addWorkerPlace(Request $request) {

    try {
      Place_Worker::where('worker_id', $request->worker_id)->delete();

      $placeIds = $request->input('selectedPlaces',[]);
      $workerId = $request->input('worker_id');

      foreach ($placeIds as $placeId) {
          $placeWorker = new Place_Worker;
          $placeWorker->worker_id = $workerId;
          $placeWorker->place_id = $placeId;
          $placeWorker->save();
      }

      return response()->json([
        'state' => __("Success"),
        'message' => __("Add Place To Worker Is Successful."),
      ], 200);


  } catch (\Exception $e) {
    return response()->json([
      'status' => 0,
      'error' => $e->getMessage(),
    ], 500); // Assuming 500 is the appropriate HTTP status code for a server error

  }
  }

  public function addCounterWorker(Request $request) {

    try {
      Worker_Counter::where('counter_id', $request->counter_id)->delete();

      $workerIds = $request->input('selectedWorkers',[]);
      $counterId = $request->input('counter_id');

      foreach ($workerIds as $workerId) {
          $counterWorker = new Worker_Counter;
          $counterWorker->worker_id = $workerId;
          $counterWorker->counter_id = $counterId;
          $counterWorker->save();
      }

      return response()->json([
        'state' => __("Success"),
        'message' => __("Add Worker To Counter Is Successful."),
      ], 200);


  } catch (\Exception $e) {
    return response()->json([
      'status' => 0,
      'error' => $e->getMessage(),
    ], 500); // Assuming 500 is the appropriate HTTP status code for a server error

  }
  }


  public function removePlaceWorker($id, $placeId) {
    try {
      $placeWorker = Place_Worker::where('worker_id', $id)->where('place_id', $placeId)->first();
      $placeWorker->delete();

      return response()->json([
        'state' => __("Success"),
        'message' => __("Remove Place From Worker Successfully."),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ], [$e->getCode()]);
    }

  }

  public function removeCounterWorker($id, $counterId) {
    try {
      $is = Counter::find($counterId)->status;
      if ($is == '1') {
        $counterWorker = Worker_Counter::where('worker_id', $id)->where('counter_id', $counterId)->first();
      } else {
        $counterWorker = Counter::find($counterId);
      }
      if ($counterWorker) {
        $counterWorker->delete();
      } else {
        return response()->json([
          'state' => 'Error',
          'error' => __("Oops! Something went wrong"),
        ], 401);
      }

      return response()->json([
        'state' => __("Success"),
        'message' => __("Counter Removed From Worker successfully!"),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ], [$e->getCode()]);
    }

  }

  public function user_places($id) {
      $user = User::find($id);
      $places = Place_Worker::where('worker_id', $id)->pluck('place_id');
      $allplaces = Place::pluck('id', 'place_name');
      return view('dashboard.users.places')
      ->with('places', $places)
      ->with('allplaces', $allplaces)
      ->with('user', $user);
  }

  public function user_counters($id) {
    $user = User::find($id);
    return view('dashboard.users.counters')
    ->with('user', $user);
  }

  public function update(Request $request) {

    $rules = [
      'id' => 'required|string',
      'fullname' => 'required|string',
      'email' => 'sometimes|email|nullable',
      'phone' => 'sometimes|nullable|string',
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

      // Check if validation fails
    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => __('Validation failed'),
        'error' => $validator->errors()->first(),
      ], 422);
    }
    try {
    $user = User::find($request->id);
    if ($user) {
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return response()->json([
          'icon' => 'success',
          'state' => __('Success'),
          'message' => __('User updated successfully.'),
        ]);
    } else {
        return response()->json([
          'icon' => 'alert',
          'state' => __('Sorry,'),
          'message' => __('There Is No User To Update.'),
        ]);

    }
  } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ],401);
  }

  }

}
