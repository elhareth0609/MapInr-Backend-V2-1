<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
  public function create(Request $request) {

    $validator = Validator::make($request->all(), [
      'amount' => 'required|numeric',
      'transaction_type' => 'required|string|in:credit,debit',
      'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => 'Validation failed : ' . $validator->errors()->first(),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {


      $wallet = new Wallet();
      $wallet->user_id = $request->user()->id;
      $wallet->amount = $request->amount;
      $wallet->transaction_type = $request->transaction_type;
      $wallet->description = $request->description;
      $wallet->save();

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

  public function create_lot(Request $request) {

    $validator = Validator::make($request->all(), [
      '*.amount' => 'required|numeric',
      '*.transaction_type' => 'required|string|in:credit,debit',
      '*.description' => 'nullable|string',
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
        $wallet = new Wallet();
        $wallet->user_id = $request->user()->id;
        $wallet->amount = $data['amount'];
        $wallet->transaction_type = $data['transaction_type'];
        $wallet->description = $data['description'];
        $wallet->save();
      }

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

  public function get(Request $request) {
    $transactions = Wallet::select('id','amount','transaction_type','description','status','created_at')
                          ->where('user_id', $request->user()->id)
                          ->get();


    $credit = Wallet::where('user_id', $request->user()->id)
                          ->where('transaction_type', 'credit')
                          ->get()->sum('amount');

    $debit = Wallet::where('user_id', $request->user()->id)
                          ->where('transaction_type', 'debit')
                          ->get()->sum('amount');

    $totalAmount = $credit - $debit;

    return response()->json([
      'status' => 1,
      'totalAmount' => $totalAmount,
      'transactions' => $transactions,
    ]);
  }
}
