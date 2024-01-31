<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Counter;

class CounterController extends Controller
{
    public function create(Request $request) {

      $validator = Validator::make($request->all(), [
          'name'      => 'required|string|max:255',
          'longitude' => 'required|numeric',
          'place_id' => [
            'required',
            'numeric',
            Rule::exists('places', 'id'),
          ],
          'latitude'  => 'required|numeric',
          'photo'     => 'sometimes|file|mimes:jpeg,png,jpg,gif',
          'note'      => 'sometimes|string',
          'phone'     => 'sometimes|string'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => 0,
          'message' => 'Validation failed',
          'error' => $validator->errors()->first(),
        ], 422);
      }

      try {

        $place = Counter::where('place_id', $request->place_id)->orderBy('counter_id', 'desc')->first()->counter_id;

        $uniqueName = null;
        if ($request->has('photo')) {
          $timeName      = time();
          $originalName  = pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME);
          $fileExtension = $request->file('photo')->getClientOriginalExtension();
          $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";
          $request->file('photo')->storeAs('public/assets/img/counters/', $uniqueName);
        }


        $counter = new Counter();
        $counter->name = $request->name;
        $counter->place_id = $request->place_id;
        $counter->worker_id = $request->user()->id;
        $counter->counter_id = ++$place;
        $counter->longitude = $request->longitude;
        $counter->latitude = $request->latitude;
        $counter->picture = $uniqueName;
        $counter->phone = $request->phone;
        $counter->note = $request->note;
        $counter->status = '0';
        $counter->save();

        return response()->json([
            'status' => 1,
            'message' => 'Created successfully',
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage(),
        ]);
      }
  }


  public function update(Request $request) {

      $validator = Validator::make($request->all(), [
        'name'      => 'required|string|max:255',
        'longitude' => 'required|numeric',
        'place_id' => [
          'required',
          'numeric',
          Rule::exists('places', 'id'),
        ],
        'counter_id' => [
          'required',
          'numeric',
          Rule::exists('counters', 'id')->where(function ($query) {
            $query->where('status', '0');
          }),
        ],
        'latitude'  => 'required|numeric',
        'photo'     => 'sometimes|file|mimes:jpeg,png,jpg,gif',
        'note'      => 'sometimes|string',
        'phone'     => 'sometimes|string'
      ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed',
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      $counter = Counter::find($request->counter_id);

      if ($request->has('photo')) {
        $uniqueName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/assets/img/counters/', $uniqueName);
      } else {
          $uniqueName = $counter->picture;
      }

      $counter->name = $request->name;
      // $counter->worker_id = $request->user()->id;
      // $counter->status = '0';
      $counter->longitude = $request->longitude;
      $counter->latitude = $request->latitude;
      $counter->picture = $uniqueName;
      $counter->phone = $request->input('phone', $counter->phone);
      $counter->note = $request->input('note', $counter->note);
      $counter->save();

      return response()->json([
          'status' => 1,
          'message' => 'Updated successfully',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
}

}
