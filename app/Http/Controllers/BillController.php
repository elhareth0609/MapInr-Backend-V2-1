<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller {

  public function create(Request $request) {

    $validator = Validator::make($request->all(), [
        'amount'      => 'required',
        'counter_id'      => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      
        $counter = Counter::where('counter_id', $request->counter_id)->first();

      if($counter) {
        $bill = new Bill();
        $bill->counter_id = $counter->id;
        $bill->amount = $request->amount;
        $bill->save();
      }

      // rewrite counter without starting new_
      $counters = Counter::where('counter_id', 'like', 'new_%')->get();
      foreach ($counters as $counter) {
        $counter->counter_id = str_replace('new_','', $counter->counter_id);
        $counter->save();
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

  public function create_lot(Request $request) {
    $validator = Validator::make($request->all(), [
        '*.amount'      => 'required',
        '*.counter_id'      => 'required',
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

          if ((str_starts_with($data['counter_id'], 'new_'))) {
            $counter = Counter::where('counter_id', $data['counter_id'])->first();
          }

          //  else 
          // if (str_starts_with($data['counter_id'], 'new_')) {
          //   $counter = Counter::find($data['counter_id']);
          // }

          if($counter) {
            $bill = new Bill();
            $bill->counter_id = $counter->id;
            $bill->amount = $data['amount'];
            $bill->save();
          }

        }

        $counters = Counter::where('counter_id', 'like', 'new_%')->get();
        foreach ($counters as $counter) {
          $counter->counter_id = str_replace('new_','', $counter->counter_id);
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

  public function all(Request $request) {
    $bills = Bill::select('id','counter_id', 'amount') ->get();

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
        $bill = Bill::find($id);
        if($bill) {
          $bill->delete();
        }
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
