<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller {

  public function create(Request $request) {

    $validator = Validator::make($request->all(), [
        'amount'      => 'required',
        'counter_id'      => 'required',
        'created_at'      => 'sometimes|date',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      if (strpos($request->counter_id, 'new_') !== false) {
        $counter = Counter::where('counter_id', $request->counter_id)->first();
      } else {
        $counter = Counter::find($request->counter_id);
      }

      if($counter) {
        $bill = new Bill();
        $bill->counter_id = $counter->id;
        $bill->user_id = $request->user()->id;
        $bill->amount = $request->amount;
        $bill->created_at = $request->created_at ?? now();
        $bill->save();
      } else {
        return response()->json([
          'status' => 0,
          'message' => 'Counter not found',
        ]);
      }

      // rewrite counter without starting new_
      $counters1 = Counter::where('counter_id', 'like', 'new_%')->get();
      foreach ($counters1 as $oneCounter) {
        $oneCounter->counter_id = str_replace('new_','', $oneCounter->counter_id);
        $oneCounter->save();
      }

      $counters2 = Counter::where('name', 'like', 'new_%')->get();
      foreach ($counters2 as $oneCounter) {
        $oneCounter->name = str_replace('new_','', $oneCounter->name);
        $oneCounter->save();
      }


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

  // [{"counter_id":"109413","counter_name":"410 [79]","amount":"10200801"},
  //  {"counter_id":"new_waypoint 54","counter_name":"waypoint 54","amount":"1000069"}]

  public function create_lot(Request $request) {
    $validator = Validator::make($request->all(), [
        '*.amount'      => 'required',
        '*.counter_id'      => 'required',
        '*.created_at' => 'sometimes|date',
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

          if (strpos($data['counter_id'], 'new_') !== false) {
            $counter = Counter::where('counter_id',$data['counter_id'])->first();
          } else {
            $counter = Counter::find($data['counter_id']);
          }

          if($counter) {
            $bill = new Bill();
            $bill->counter_id = $counter->id;
            $bill->user_id = $request->user()->id;
            $bill->amount = $data['amount'];
            $bill->created_at = $data['created_at'] ?? now(); // Use the current timestamp if not provided
            $bill->save();
          }
          // else {
          //   $bill = new Bill();
          //   $bill->counter_id = 'not found' . $data['counter_id'];
          //   $bill->amount = $data['amount'];
          //   $bill->save();
          // }

        }

        $counters1 = Counter::where('counter_id', 'like', 'new_%')->get();
        foreach ($counters1 as $oneCounter) {
          $oneCounter->counter_id = str_replace('new_','', $oneCounter->counter_id);
          $oneCounter->save();
        }

        $counters2 = Counter::where('name', 'like', 'new_%')->get();
        foreach ($counters2 as $oneCounter) {
          $oneCounter->name = str_replace('new_','', $oneCounter->name);
          $oneCounter->save();
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
    $bills = Bill::select('id','counter_id','user_id','amount')->where('user_id', $request->user()->id)->get();

    foreach ($bills as $bill) {
      $counter = Counter::find($bill->counter_id);
      $bill->counter_id = $counter->name;
    }

    return response()->json([
      'bills' => $bills
    ]);
  }

  public function delete(Request $request,$id) {
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

    try {
        $bill = Bill::findOrFail($id);
        $bill->delete();

        return response()->json([
          'state' => __('Success'),
          'message' => __('Bill deleted successfully.'),
        ]);

    } catch (\Exception $e) {
      return response()->json([
        'state' => __("Error"),
        'message' => $e->getMessage(),
      ]);
    }
  }

  public function deleteAll(Request $request) {
    try {
      if(empty($request->ids)) {
        return response()->json([
        'state' => __("Error"),
        'message' => __("There Is No Selected Rows."),
        ], 401);
      }

      foreach ($request->ids as $id) {
        $bill = Bill::find($id);
        if ($bill) {
          $bill->delete();
        }
      }

      return response()->json([
        'state' => __("Success"),
        'message' => __("Bills Removed successfully!"),
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ], 500);
    }
  }

}
