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
          'id' => [
            'sometimes',
            'numeric',
            Rule::exists('counters', 'id')->where(function ($query) {
              $query->where('status', '1');
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
          'message' => 'Validation failed : ' . $validator->errors()->first(),
          'error' => $validator->errors()->first(),
        ], 422);
      }

      try {

        $place = Counter::where('place_id', 0)->orderBy('counter_id', 'desc')->first()->counter_id;

        $uniqueName = null;
        if ($request->has('photo')) {
          $timeName      = time();
          $originalName  = pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME);
          $fileExtension = $request->file('photo')->getClientOriginalExtension();
          $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";
          $request->file('photo')->storeAs('public/assets/img/counters/', $uniqueName);
        }

        // if ($request->id) {
        //   $selectCounter = Counter::find($request->id);
        //   $id = $selectCounter->counter_id;
        //   $is_exist = Counter::where('counter_id',$selectCounter->counter_id)
        //               ->where('place_id',0)
        //               ->first();
        //   if (!$is_exist) {
        //     $counter = new Counter();
        //   } else {
        //     $counter = Counter::where('counter_id',$selectCounter->counter_id)
        //                       ->where('place_id',0)
        //                       ->first();
        //   }
        // } else {
        //   $id = ++$place;
        //   $counter = new Counter();
        // }

        if ($request->id) {
          $counterSelected = Counter::find($request->id);
          $id = $counterSelected->counter_id;
        } else {
          $id = 0;
        }

        $counter = new Counter();
        $counter->name = $request->name;
        $counter->place_id = 0;
        $counter->worker_id = $request->user()->id;
        $counter->counter_id = $id;
        $counter->longitude = $request->longitude;
        $counter->latitude = $request->latitude;
        $counter->picture = $uniqueName ;
        // $counter->picture = $uniqueName ? $uniqueName : $counterSelected->picture;
        $counter->phone = $request->phone;
        // $counter->phone = $request->input('phone',$counterSelected->phone);
        $counter->note = $request->note;
        // $counter->note = $request->input('note',$counterSelected->note);
        $counter->status = '0';
        $counter->save();

        return response()->json([
            'status' => 1,
            'message' => 'Created successfully : ' . $counter,
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
        'message' => 'Validation failed : ' . $validator->errors()->first(),
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
      $counter->longitude = $request->longitude;
      $counter->latitude = $request->latitude;
      $counter->picture = $uniqueName;
      $counter->phone = $request->input('phone', $counter->phone);
      $counter->note = $request->input('note', $counter->note);
      $counter->save();

      return response()->json([
          'status' => 1,
          'message' => 'Updated successfully : ' . $counter,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
}

  public function destroy(Request $request){
    try {
      $counter = Counter::where('counter_id', $request->id)->where('place_id','0')->first();
      // $counter = Counter::find($request->id);
      if (!$counter) {
        return response()->json([
          'status' => 0,
          'message' => 'There is no counter with this id. ',
        ],422);
      }


      $counter->delete();
      return response()->json([
        'status' => 1,
        'message' => 'Deleted successfully.',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
  }
}
