<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Place_Worker;

class UserController extends Controller
{
  //
  public function user($id) {
    $user = User::find($id);
    return view('dashboard.users.index')
    ->with('user',$user);
  }

  public function generate_password()
  {
      // Generate a random password (you can customize the length and characters)
      $generatedPassword = Str::random(12);

      // Check if the generated password already exists in the database
      $passwordExists = User::where('password', Hash::make($generatedPassword))->exists();

      // If the generated password already exists, generate a new one
      while ($passwordExists) {
          $generatedPassword = Str::random(12);
          $passwordExists = User::where('password', Hash::make($generatedPassword))->exists();
      }

      return response()->json(['password' => $generatedPassword]);
  }

  public function create(Request $request) {

    $rules = [
      'firstname' => 'required|string',
      'lastname' => 'required|string',
      'email' => 'required|email|unique:users',
      'phone' => 'required|string',
      'password' => 'required|string|min:8',
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

      // Check if validation fails
    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed',
        'error' => $validator->errors()->first(),
      ], 422);
    }
    $user = new User();
    $user->fullname = $request->firstname . ' ' . $request->lastname;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = $request->password;
    $user->save();

    return response()->json([
      'status' => 1,
      'message' => 'User created successfully',
    ]);
  }

  public function destroy($id) {
    $user = User::find($id);
    if ($user) {
        $user->delete();

        return response()->json([
          'status' => 1,
          'message' => 'User deleted successfully.',
        ]);
    } else {
        return response()->json([
          'status' => 1,
          'message' => 'Sorry,',
          'error' => 'There Is No User To Deleted.',
        ],401);
    }
  }

  public function addPlaceWorker($id, $placeId) {

    try {
      $placeWorker = new Place_Worker;
      $placeWorker->worker_id = $id;
      $placeWorker->place_id = $placeId;
      $placeWorker->save();

      return response()->json([
        'status' => 1,
        'message' => 'Add Place To Worker Is Successfull.',
      ]);
  } catch (\Exception $e) {
    return response()->json([
      'status' => 1,
      'error' => $e->getMessage(),
    ], [$e->getCode()]);
  }
  }

  public function removePlaceWorker($id, $placeId) {
    try {
      $placeWorker = Place_Worker::where('worker_id', $id)->where('place_id', $placeId)->first();
      $placeWorker->delete();

      return response()->json([
        'status' => 1,
        'message' => 'Remove Place From Worker Successfully.',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 1,
        'error' => $e->getMessage(),
      ], [$e->getCode()]);
    }

  }

  public function user_places($id) {
      $user = User::find($id);
      return view('dashboard.users.places')
      ->with('user', $user);
  }

  public function update(Request $request) {

    $rules = [
      'id' => 'required|string',
      'fullname' => 'required|string',
      'email' => 'required|email',
      'phone' => 'required|string',
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

      // Check if validation fails
    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed',
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
          'status' => 1,
          'message' => 'User updated successfully.',
        ],401);
    } else {
        return response()->json([
          'status' => 1,
          'message' => 'There is no user with this id.',
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
