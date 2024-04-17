<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $place = Counter::where('place_id', 0)->orderBy('counter_id', 'desc')->first();
        if($place) {
          $place->counter_id;
        }
        $uniqueName = null;
        // if ! $request->id do this
        if ($request->has('photo')) {
          $timeName      = time();
          $originalName  = pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME);
          $fileExtension = $request->file('photo')->getClientOriginalExtension();
          $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";
          $request->file('photo')->storeAs('public/assets/img/counters/', $uniqueName);
        }

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
        $counter->phone = $request->phone;
        $counter->note = $request->note;
        $counter->status = '0';
        $counter->save();

        return response()->json([
            'status' => 1,
            'message' => 'Created successfully',
            'counter' => $counter->id
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage(),
        ]);
      }
    }

  public function create_lot(Request $request) {
    $validator = Validator::make($request->all(), [
        '*.name'      => 'required|string|max:255',
        '*.id' => [
          'sometimes',
          'numeric',
          Rule::exists('counters', 'id')->where(function ($query) {
            $query->where('status', '1');
          }),
        ],
        '*.longitude' => 'required|numeric',
        '*.latitude'  => 'required|numeric',
        '*.photo'     => 'sometimes|file|mimes:jpeg,png,jpg,gif',
        '*.note'      => 'sometimes|string',
        '*.phone'     => 'sometimes|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => 'Validation failed : ' . $validator->errors()->first(),
            'error' => $validator->errors()->first(),
        ], 422);
    }

    try {
        foreach ($request->all() as $data) {
            $place = Counter::where('place_id', 0)->orderBy('counter_id', 'desc')->first();

            if($place) {
              $place->counter_id;
            }


            $uniqueName = null;
            // if ! isset($data['id']) do this
            if (isset($data['photo'])) {
                $timeName      = time();
                $originalName  = pathinfo($data['photo']->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $data['photo']->getClientOriginalExtension();
                $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";
                $data['photo']->storeAs('public/assets/img/counters/', $uniqueName);
            }

            if (isset($data['id'])) {
                $counterSelected = Counter::find($data['id']);
                $id = $counterSelected ? $counterSelected->counter_id : 0;
            } else {
                $id = 0;
            }

            $counter = new Counter();
            $counter->name = $data['name'];
            $counter->place_id = 0;
            $counter->worker_id = $request->user()->id;
            $counter->counter_id = $id;
            $counter->longitude = $data['longitude'];
            $counter->latitude = $data['latitude'];
            $counter->picture = $uniqueName;
            $counter->phone = isset($data['phone']) ? $data['phone'] : null;
            $counter->note = isset($data['note']) ? $data['note'] : null;
            $counter->status = '0';
            $counter->save();

        }

        return response()->json([
            'status' => 1,
            'message' => 'Created successfully' ,
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
      // else if !counter put uniqe name null
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
          'message' => 'Updated successfully',
          'counter' => $counter->id
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
  }

  public function update_lot(Request $request) {

      $validator = Validator::make($request->all(), [
        '*.name'      => 'required|string|max:255',
        '*.longitude' => 'required|numeric',
        '*.counter_id' => [
          'required',
          'numeric',
          Rule::exists('counters', 'id')->where(function ($query) {
            $query->where('status', '0');
          }),
        ],
        '*.latitude'  => 'required|numeric',
        '*.photo'     => 'sometimes|file|mimes:jpeg,png,jpg,gif',
        '*.note'      => 'sometimes|string',
        '*.phone'     => 'sometimes|string'
      ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      foreach ($request->all() as $data) {

          $counter = Counter::find($data['counter_id']);

          if (isset($data['photo'])) {
            $uniqueName = time() . '_' . $data['photo']->getClientOriginalName();
            $data['photo']->storeAs('public/assets/img/counters/', $uniqueName);
          // else if !counter put uniqe name null
          } else {
              $uniqueName = $counter->picture;
          }

          $counter->name = $data['name'];
          $counter->longitude = $data['longitude'];
          $counter->latitude = $data['latitude'];
          $counter->picture = $uniqueName;
          $counter->phone = isset($data['phone']) ? $data['phone'] : $counter->phone;
          $counter->note = isset($data['note']) ? $data['note'] : $counter->note;
          $counter->save();

    }
      return response()->json([
          'status' => 1,
          'message' => 'Updated successfully.' ,
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
      // $counter = Counter::where('counter_id', $request->id)->where('place_id','0')->first();
      $counter = Counter::find($request->id);
      if (!$counter) {
        return response()->json([
          'status' => 0,
          'message' => 'There is no counter with this id. ',
        ],422);
      }

      $photoPath = 'public/assets/img/counters/' . $counter->picture;

      // Check if the photo exists and delete it
      if ($counter->picture && Storage::exists($photoPath)) {
          Storage::delete($photoPath);
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

  public function destroy_lot(Request $request){
    try {
      foreach ($request->all() as $data) {
      $counter = Counter::find($data['id']);

      $photoPath = 'public/assets/img/counters/' . $counter->picture;

      // Check if the photo exists and delete it
      if ($counter->picture && Storage::exists($photoPath)) {
          Storage::delete($photoPath);
      }
      $counter->delete();
    }
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

  public function delete_all(Request $request) {
    try {
      foreach ($request->ids as $id) {
          $counter = Counter::find($id);
          if ($counter) {
              $counter->delete();
          }
      }

      return response()->json([
          'state' => __("Success"),
          'message' => __("Deleted Successfully")
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ],401);
    }
  }

  public function share(Request $request) {

    $validator = Validator::make($request->all(), [
      'phone' => 'sometimes|string'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {
      $worker = User::where('phone', $request->phone)->first();
      $counter = Counter::where('place_id', 0)->orderBy('counter_id', 'desc')->first();

      $newCounter = $counter->replicate();
      if($counter->picture) {

        $timeName = time();
        $originalName = pathinfo($counter->picture, PATHINFO_FILENAME);
        $fileExtension = pathinfo($counter->picture, PATHINFO_EXTENSION);
        $uniqueName = "{$timeName}_{$originalName}.{$fileExtension}";

        $sourcePath = storage_path('app/public/assets/img/counters/' . $counter->picture);
        $destinationPath = storage_path('app/public/assets/img/counters/' . $uniqueName);
        copy($sourcePath, $destinationPath);

        $newCounter->picture = $uniqueName;
      }
      $newCounter->worker_id = $worker->id;
      $newCounter->save();


      return response()->json([
          'status' => 1,
          'message' => 'Shared successfully.' ,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
  }

}
