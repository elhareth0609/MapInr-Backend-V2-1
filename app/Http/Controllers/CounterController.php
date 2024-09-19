<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Municipality;
use App\Models\Place;
use App\Models\Place_Worker;
use App\Models\Shared;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CounterController extends Controller {
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
        // $id = 0;
        $id = $request->name;
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
        '*.audio'     => 'sometimes|file',
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
            $uniqueAudioName = null;

            if (isset($data['photo'])) {
                $timeName      = time();
                $originalName  = pathinfo($data['photo']->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $data['photo']->getClientOriginalExtension();
                $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";
                $data['photo']->storeAs('public/assets/img/counters/', $uniqueName);
            }

            if (isset($data['audio'])) {
              $audiotimeName      = time();
              $audiooriginalName  = pathinfo($data['audio']->getClientOriginalName(), PATHINFO_FILENAME);
              $audiofileExtension = $data['audio']->getClientOriginalExtension();
              $uniqueAudioName = "{$audiotimeName}_{$audiooriginalName}.{$audiofileExtension}";
              $data['audio']->storeAs('public/assets/audio/counters/', $uniqueAudioName);
            }

            if (isset($data['id'])) {
                $counterSelected = Counter::find($data['id']);
                $id = $counterSelected ? $counterSelected->counter_id : 0;
            } else {
                // $id = 0;
                $id = $data['name'];
            }

            $counter = new Counter();
            $counter->name = $data['name'];
            $counter->place_id = 0;
            $counter->worker_id = $request->user()->id;
            $counter->counter_id = $id;
            $counter->longitude = $data['longitude'];
            $counter->latitude = $data['latitude'];
            $counter->picture = $uniqueName;
            $counter->audio = $uniqueAudioName;
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
      $counter->counter_id = $request->name;
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
          $counter->counter_id = $data['name'];
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
      $audioPath = 'public/assets/audio/counters/' . $counter->audio;

      // Check if the photo exists and delete it
      if ($counter->picture && Storage::exists($photoPath)) {
          Storage::delete($photoPath);
      }

      if ($counter->audio && Storage::exists($audioPath)) {
        Storage::delete($audioPath);
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
      $audioPath = 'public/assets/audio/counters/' . $counter->audio;

      // Check if the photo exists and delete it
      if ($counter->picture && Storage::exists($photoPath)) {
          Storage::delete($photoPath);
      }

      if ($counter->audio && Storage::exists($audioPath)) {
        Storage::delete($audioPath);
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
            $photoPath = 'public/assets/img/counters/' . $counter->picture;
            $audioPath = 'public/assets/audio/counters/' . $counter->audio;

            // Check if the photo exists and delete it
            if ($counter->picture && Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }

            if ($counter->audio && Storage::exists($audioPath)) {
              Storage::delete($audioPath);
            }

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
      'user_id' => 'required|string',
      'type' => 'required|string|in:counter,all,place',
      'id' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {
      $worker = User::find($request->user_id);
      if ($request->type == 'counter') {
        $counter = Counter::find($request->id);

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

        if($counter->audio) {
          $audiotimeName = time();
          $audiooriginalName = pathinfo($counter->audio, PATHINFO_FILENAME);
          $audiofileExtension = pathinfo($counter->audio, PATHINFO_EXTENSION);
          $audiouniqueName = "{$audiotimeName}_{$audiooriginalName}.{$audiofileExtension}";

          $audiosourcePath = storage_path('app/public/assets/audio/counters/' . $counter->audio);
          $audiodestinationPath = storage_path('app/public/assets/audio/counters/' . $audiouniqueName);
          copy($audiosourcePath, $audiodestinationPath);

          $newCounter->audio = $audiouniqueName;
        }

        $newCounter->worker_id = $worker->id;
        $newCounter->status = '0';
        $newCounter->save();

        $share = new Shared();
        $share->counter_id = $newCounter->id;
        $share->user_id = $worker->id;
        $share->save();

      } else if($request->type == 'place') {
        $palce = Place::find($request->id);
        if ($palce) {
          $findPlaceWorker = Place_Worker::where('worker_id', $worker->id)->where('place_id', $palce->id)->first();
          if(!$findPlaceWorker) {
            $newPlaceWorker = new Place_Worker();
            $newPlaceWorker->worker_id = $worker->id;
            $newPlaceWorker->place_id = $palce->id;
            $newPlaceWorker->save();
          }
        }
      } else {
        $counters = Counter::where('worker_id',Auth::user()->id)->get();
        foreach ($counters as $counter) {
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

          if($counter->audio) {
            $audiotimeName = time();
            $audiooriginalName = pathinfo($counter->audio, PATHINFO_FILENAME);
            $audiofileExtension = pathinfo($counter->audio, PATHINFO_EXTENSION);
            $audiouniqueName = "{$audiotimeName}_{$audiooriginalName}.{$audiofileExtension}";

            $audiosourcePath = storage_path('app/public/assets/audio/counters/' . $counter->audio);
            $audiodestinationPath = storage_path('app/public/assets/audio/counters/' . $audiouniqueName);
            copy($audiosourcePath, $audiodestinationPath);

            $newCounter->audio = $audiouniqueName;
          }

          $newCounter->worker_id = $worker->id;
          $newCounter->status = '0';
          $newCounter->save();

          $share = new Shared();
          $share->counter_id = $newCounter->id;
          $share->user_id = $worker->id;
          $share->save();
        }

      }

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

  public function saveAudioNumber(Request $request) {
    $validator = Validator::make($request->all(), [
      'counter_id' => 'required|exists:counters,id',
      'number' => 'required|string'
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
      $counter->name = 'counter_' . $request->number;
      $counter->counter_id = $request->number;
      $counter->save();

      return response()->json([
          'state' => __("Success"),
          'message' => __("Number Saved Successfully")
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'state' => __("Error"),
        'message' => $e->getMessage(),
      ]);
    }
  }

  public function saveCounterPhone(Request $request) {
    $validator = Validator::make($request->all(), [
      'counter_id' => 'required|exists:counters,id',
      'number' => 'required|string'
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
      $counter->phone = $request->number;
      $counter->save();

      return response()->json([
          'state' => __("Success"),
          'message' => __("Phone Saved Successfully.")
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'state' => __("Error"),
        'message' => $e->getMessage(),
      ]);
    }
  }

  public function saveCounterPlace(Request $request) {

    try {
      $editedData = $request->input('editedData');

      foreach ($editedData as $data) {
          $counter = Counter::find($data['counter']);
          if ($counter && strlen($data['send_to']) > 2) {
              $pid = substr($data['send_to'], 2);

              $municapiltyChar = substr($data['send_to'], 0, 2);
              $municapilty = Municipality::where('code', $municapiltyChar)->first();

              if ($municapilty) {
                $place = Place::where('place_id', $pid)
                ->where('municipality_id', $municapilty->id)
                ->first();

                if ($place) {
                  $checkCounter = Counter::where('counter_id', $counter->counter_id)->where('place_id', $place->id)->first();

                  if ($checkCounter) {
                    continue;
                    // return response()->json([
                    //     'title' => __('Exsits Before.'),
                    //     'error' => __('Counter Id Existing Before.')
                    //   ], 404);
                  }
                  $newCounter = $counter->replicate();

                  $newCounter->place_id = $place->id;
                  $newCounter->status = 1;
                  $newCounter->worker_id = null;

                  if ($counter->photo && Storage::exists("public/assets/img/counters/{$counter->photo}")) {
                    $timeName      = time();
                    $originalName  = pathinfo($counter->photo, PATHINFO_FILENAME);
                    $fileExtension = pathinfo($counter->photo, PATHINFO_EXTENSION);
                    $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";

                    Storage::copy("public/assets/img/counters/{$counter->photo}", "public/assets/img/counters/{$uniqueName}");

                    $newCounter->photo = $uniqueName;
                  }

                  $newCounter->save();
                  // $counter->delete();
                }
              }
          }
      }

      return response()->json([
          'state' => __("Success"),
          'message' => __("Updated successfully")
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'state' => __("Error"),
        'message' => $e->getMessage(),
      ]);
    }
  }

  public function search(Request $request) {
    $validator = Validator::make($request->all(), [
      'mot' => 'required|string|min:4'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    $counters = null;

    try {
        $pcid = substr($request->mot, 2);
        $municapiltyChar = substr($request->mot, 0, 2);
        $municapilty = Municipality::where('code', $municapiltyChar)->first();
        if ($municapilty) {
          if (strlen($pcid) == 2) {
            $place = Place::where('place_id',$pcid)->where('municipality_id',$municapilty->id)->first();
            if ($place) {
              $counters = Counter::where('place_id',$pcid)->get();
              foreach ($counters as $counter) {
                $counter->phone = $counter->myPhone? $counter->myPhone->phone : $counter->phone;
              }
            }
          } else {
            $pid = substr($pcid, 0, 2);
            $place = Place::where('place_id',$pid)->first();
            if ($place) {
              $cid = substr($pcid, 2);
              $counters = Counter::where('place_id',$place->id)->where('counter_id', 'like', "{$cid}%")->get();
              foreach ($counters as $counter) {
                $counter->phone = $counter->myPhone? $counter->myPhone->phone : $counter->phone;
              }
            }
            // return $counters;
          }
        }

        return response()->json([
          'status' => 1,
          'message' => 'Searched successfully',
          'counters' => $counters
        ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }

  }
}
