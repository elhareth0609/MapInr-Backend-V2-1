<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneController extends Controller {
  public function create(Request $request) {

    $validator = Validator::make($request->all(), [
        'phone'      => 'required|phone',
        'value'      => 'required|string',
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
        '*.value'      => 'required|string',
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

}
