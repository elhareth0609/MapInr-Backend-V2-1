<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Municipality;
use App\Models\Phone;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneController extends Controller {
  public function create(Request $request) {

    $validator = Validator::make($request->all(), [
        'phone'      => 'required|string',
        'value'      => 'sometimes|string',
        'audio'     => 'sometimes|file',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      $uniqueName = null;

      if ($request->has('audio')) {
        $timeName      = time();
        $originalName  = pathinfo($request->file('audio')->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = $request->file('audio')->getClientOriginalExtension();
        $uniqueName    = "{$timeName}_{$originalName}.{$fileExtension}";
        $request->file('audio')->storeAs('public/assets/audio/phones/', $uniqueName);
      }

      $phone = new Phone();
      $phone->worker_id = $request->user()->id;
      $phone->phone = $request->phone;
      $phone->value = $request->value;
      $phone->audio = $uniqueName;
      $phone->save();

      return response()->json([
          'status' => 1,
          'message' => 'Created successfully'
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
        '*.phone'      => 'required|string',
        '*.value'      => 'sometimes|string',
        '*.audio'     => 'sometimes|file',
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
            $uniqueAudioName = null;

            if (isset($data['audio'])) {
              $audiotimeName      = time();
              $audiooriginalName  = pathinfo($data['audio']->getClientOriginalName(), PATHINFO_FILENAME);
              $audiofileExtension = $data['audio']->getClientOriginalExtension();
              $uniqueAudioName = "{$audiotimeName}_{$audiooriginalName}.{$audiofileExtension}";
              $data['audio']->storeAs('public/assets/audio/phones/', $uniqueAudioName);
            }

            $phone = new Phone();
            $phone->worker_id = $request->user()->id;
            $phone->phone = $data['phone'];
            $phone->value = $data['value'];
            $phone->audio = $uniqueAudioName;
            $phone->save();

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

  public function all(Request $request) {
    $phones = Phone::whereNotNull('value')
    ->select('id', 'value', 'phone')
    ->get();
    return response()->json([
      'phones' => $phones
    ]);
  }

  public function saveAudioValue(Request $request) {
    $validator = Validator::make($request->all(), [
      'phone_id' => 'required|exists:phones,id',
      'value' => 'required|string'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {
      $phone = Phone::find($request->phone_id);
      $phone->value = $request->value;
      $phone->save();

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

  public function savePhoneCounter(Request $request) {
    $validator = Validator::make($request->all(), [
      'phone_id' => 'required|exists:phones,id',
      'mot' => 'required|string|min:4'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      $pcid = substr($request->mot, 2);
      $municapiltyChar = substr($request->mot, 0, 2);

      $municapilty = Municipality::where('code', $municapiltyChar)->first();
      if (!$municapilty) {
          return response()->json(['error' => 'Municipality not found'], 404);
      }

      if (strlen($pcid) > 2) {
          $pid = substr($pcid, 0, 2);
          $place = Place::where('place_id', $pid)
              ->where('municipality_id', $municapilty->id)
              ->first();

          if (!$place) {
              return response()->json(['error' => 'Place not found'], 404);
          }

          $cid = substr($pcid, 2);
          $counter = Counter::where('counter_id', $cid)->first();

          if (!$counter) {
              return response()->json(['error' => 'Counter not found'], 404);
          }

          $phone = Phone::find($request->phone_id);
          if (!$phone) {
              return response()->json(['error' => 'Phone not found'], 404);
          }

          $phone->counter_id = $counter->id;
          $phone->mot = $request->mot;
          $phone->save();

          return response()->json([
            'state' => __("Success"),
            'message' => __("Mot Saved Successfully")
          ]);
      } else {
          return response()->json(['error' => 'Invalid pcid length'], 400);
      }

    } catch (\Exception $e) {
      return response()->json([
        'state' => __("Error"),
        'message' => $e->getMessage(),
      ]);
    }
  }
}
