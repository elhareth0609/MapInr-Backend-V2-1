<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Counter;

class CounterController extends Controller
{
    public function create(Request $request) {

      $validator = Validator::make($request->all(), [
          'name'      => 'required|string|max:255',
          'longitude' => 'required|numeric',
          'place_id' => 'required|numeric',
          'latitude'  => 'required|numeric',
          'photo'     => 'required|image|mimes:jpeg,png,jpg,gif',
          'note'      => 'sometimes|string',
          'phone'     => 'required|string'
        ]);

        // Check if validation fails
      if ($validator->fails()) {
        return response()->json([
          'status' => 0,
          'message' => 'Validation failed',
          'error' => $validator->errors()->first(),
        ], 422);
      }

      $counter = new Counter();
      $counter->name = $request->name;
      $counter->place_id = $request->place_id;
      $counter->counter_id = 23;
      $counter->longitude = $request->longitude;
      $counter->latitude = $request->latitude;
      $counter->picture = $request->photo;
      $counter->phone = $request->phone;
      $counter->note = $request->note;
      $counter->status = '0';
      $counter->save();

      return response()->json([
          'status' => 1,
          'message' => 'Created successfully',
      ]);
    }
}
