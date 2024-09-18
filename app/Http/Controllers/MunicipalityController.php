<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MunicipalityController extends Controller {

  public function create(Request $request) {

    $rules = [
      'name' => 'required|string',
      'code' => 'required|string|unique:municipalities,code'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => __('Validation failed'),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    $municipality = new Municipality();
    $municipality->name = $request->name;
    $municipality->code = $request->code;
    $municipality->save();

    return response()->json([
      'state' => __('Success'),
      'message' => __('Municipality created successfully'),
    ]);
  }

  public function destroy(Request $request, $id) {
    // Validate the request data
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
        // Check if the password is correct
        // $admin = User::find(Auth::user()->id);
        // if ($admin && Hash::check($request->password, $admin->password)) {
            // Password is correct, proceed with deleting the municipality
            $municipality = Municipality::find($id);
            if ($municipality) {
                $municipality->delete();

                return response()->json([
                    'status' => __('Success'),
                    'message' => __('Municipality deleted successfully.'),
                ]);
            } else {
                return response()->json([
                    'status' => __('Error'),
                    'message' => __('Sorry, the municipality does not exist.'),
                ], 404);
            }
        // } else {
        //     // Password is incorrect
        //     return response()->json([
        //         'status' => 1,
        //         'message' => __('Error'),
        //         'errors' => __('Password Incorrect')
        //     ], 422);
        // }
    } catch (\Exception $e) {
        // Handle any other exceptions
        return response()->json([
            'status' => 1,
            'message' => __('Error'),
            'errors' => $e->getMessage()
        ], 422);
    }
  }

  public function update(Request $request) {

    $rules = [
      'id' => 'required|string',
      'name' => 'required|string'
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
      $municipality = Municipality::find($request->id);
      if ($municipality) {
          $municipality->name = $request->name;
          $municipality->save();

          return response()->json([
            'icon' => 'success',
            'state' => __('Success'),
            'message' => __('Municipality updated successfully.'),
          ]);
      } else {
          return response()->json([
            'icon' => 'alert',
            'state' => __('Sorry,'),
            'message' => __('There Is No Municipality To Update.'),
          ]);

      }
    } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'error' => $e->getMessage(),
        ],401);
    }

  }

  public function municipality($id,Request $request) {
    $municipality = Municipality::find($id);
    return view('dashboard.municipalitys.index')
    ->with('municipality',$municipality);
  }

  public function municipality_places(Request $request) {
    return view('dashboard.municipalitys.places');
  }
}
