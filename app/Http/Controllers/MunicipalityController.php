<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Municipality;

class MunicipalityController extends Controller
{

  public function create(Request $request) {

    $rules = [
      'name' => 'required|string'
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
    $municipality->save();

    return response()->json([
      'state' => __('Success'),
      'message' => __('Municipality created successfully'),
    ]);
  }

  public function destroy($id) {
    $municipality = Municipality::find($id);
    if ($municipality) {
        $municipality->delete();

        return response()->json([
          'state' => __('Success'),
          'message' => __('Municipality deleted successfully.'),
        ]);
    } else {
        return response()->json([
          'status' => __('Success'),
          'message' => __('Sorry,'),
          'error' => __('There Is No Municipality To Deleted.'),
        ],401);
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
