<?php

namespace App\Http\Controllers;

use App\Models\AudioTransactions;
use App\Models\PhotoTransactions;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
  public function add(Request $request) {

    $validator = Validator::make($request->all(), [
      'amount' => 'required|numeric',
      'id' => 'required|numeric',
      'type' => 'required|string|in:credit,debit',
      'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => __('Validation failed'),
        'error' => $validator->errors()->first(),
      ], 422);
    }

    try {

      $wallet = new Wallet();
      $wallet->user_id = $request->id;
      $wallet->amount = $request->amount;
      $wallet->transaction_type = $request->type;
      $wallet->description = $request->description;
      $wallet->save();

      return response()->json([
          'status' => 1,
          'message' => __("Created Successfully"),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
  }

  public function create(Request $request) {

    $validator = Validator::make($request->all(), [
      'amount' => 'required|numeric',
      'transaction_type' => 'required|string|in:credit,debit',
      'description' => 'nullable|string',
      'photo' => 'sometimes|array',
      'photo.*' => 'sometimes|file|mimetypes:image/*',
      'audio' => 'sometimes|array',
      'audio.*' => 'sometimes|file',

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

      $uniqueName = null;
      $uniqueAudioName = null;

      if ($request->hasFile('audio')) {
        foreach ($request->file('audio') as $audio) {
            $timeName = time();
            $originalName = pathinfo($audio->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtension = $audio->getClientOriginalExtension();
            $uniqueAudioName = "{$timeName}_{$originalName}_" . uniqid() . ".{$fileExtension}";

            $path = $audio->storeAs('assets/audio/wallets/', $uniqueAudioName, 'public');
            Log::info("Audio uploaded: {$uniqueAudioName}");

            $audioTransaction = new AudioTransactions();
            $audioTransaction->wallet_id = $wallet->id;
            $audioTransaction->audio = $uniqueAudioName;
            $audioTransaction->save();
        }
      }

      if ($request->hasFile('photo')) {
        foreach ($request->file('photo') as $photo) {
              $timeName = time();
              $originalName = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
              $fileExtension = $photo->getClientOriginalExtension();
              $uniqueName = "{$timeName}_{$originalName}_" . uniqid() . ".{$fileExtension}";

              $path = $photo->storeAs('assets/img/wallets/', $uniqueName, 'public');
              Log::info("Photo uploaded: {$uniqueName} to {$path}");

              $photoTransaction = new PhotoTransactions();
              $photoTransaction->wallet_id = $wallet->id;
              $photoTransaction->photo = $uniqueName;
              $photoTransaction->save();
          }
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

  public function create_lot(Request $request) {

    $validator = Validator::make($request->all(), [
      '*.amount' => 'required|numeric',
      '*.transaction_type' => 'required|string|in:credit,debit',
      '*.description' => 'nullable|string',
      '*.photo' => 'sometimes|array',
      '*.photo.*' => 'sometimes|file|mimetypes:image/*',
      // 'photo.*' => 'sometimes|file|mimes:jpeg,png,jpg,gif',
      // 'audio.*' => 'sometimes|file',
      '*.audio' => 'sometimes|array',
      '*.audio.*' => 'sometimes|file',

      // '*.photo.*'     => 'sometimes|file|mimes:jpeg,png,jpg,gif',
      // '*.audio.*'     => 'sometimes|file',
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

          $uniqueName = null;
          $uniqueAudioName = null;

          if (isset($data['photo'])) {
            foreach ($data['photo'] as $photo) {
              $timeName = time();
              $originalName = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
              $fileExtension = $photo->getClientOriginalExtension();
              $uniqueName = "{$timeName}_{$originalName}_" . uniqid() . ".{$fileExtension}";

              $path = $photo->storeAs('assets/img/wallets/', $uniqueName, 'public');
              Log::info("Photo uploaded: {$uniqueName} to {$path}");

              $photoTransaction = new PhotoTransactions();
              $photoTransaction->wallet_id = $wallet->id;
              $photoTransaction->photo = $uniqueName;
              $photoTransaction->save();
            }
          }

          if (isset($data['audio'])) {
              foreach ($data['audio'] as $audio) {
                $timeName = time();
                $originalName = pathinfo($audio->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $audio->getClientOriginalExtension();
                $uniqueAudioName = "{$timeName}_{$originalName}_" . uniqid() . ".{$fileExtension}";

                $path = $audio->storeAs('assets/audio/wallets/', $uniqueAudioName, 'public');
                Log::info("Audio uploaded: {$uniqueAudioName}");

                $audioTransaction = new AudioTransactions();
                $audioTransaction->wallet_id = $wallet->id;
                $audioTransaction->audio = $uniqueAudioName;
                $audioTransaction->save();
              }
          }
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
    $transactions = Wallet::select('id', 'amount', 'transaction_type', 'description', 'status', 'created_at')
    ->where('user_id', $request->user()->id)
    ->where('status', '!=','hidden')
    ->latest('created_at')
    ->get();

    $credit = Wallet::where('user_id', $request->user()->id)
                          ->where('transaction_type', 'credit')
                          ->where('status', 'completed')
                          ->get()->sum('amount');

    $debit = Wallet::where('user_id', $request->user()->id)
                          ->where('transaction_type', 'debit')
                          ->where('status', 'completed')
                          ->get()->sum('amount');

    $totalAmount = $credit - $debit;

    return response()->json([
      'status' => 1,
      'totalAmount' => $totalAmount,
      'transactions' => $transactions,
    ]);
  }

  public function unuploadFile(Request $request,$id) {
    try {
      if ($request->type == 'audio') {
        $audio = AudioTransactions::find($id);

        $audioPath = 'public/assets/audio/wallets/' . $audio->audio;

        if ($audio->audio && Storage::exists($audioPath)) {
          Storage::delete($audioPath);
        }

        $audio->delete();
      } elseif ($request->type == 'image') {
        $photo = PhotoTransactions::find($id);

        $photoPath = 'public/assets/img/wallets/' . $photo->photo;

        if ($photo->photo && Storage::exists($photoPath)) {
            Storage::delete($photoPath);
        }

        $photo->delete();
      }

    return response()->json([
      'icon' => 'success',
      'state' => __("Success"),
      'message' => __("Deleted Successfully.")
    ]);

    } catch (\Exception $e) {
      return response()->json([
        'icon' => 'error',
        'state' => __("Error"),
        'message' => $e->getMessage(),
      ]);
    }

  }

  public function submit(Request $request,$id) {
    $validator = Validator::make($request->all(), [
      'amount' => 'required|string',
      'type' => 'required|string',
      'description' => 'required|string',
      'status' => 'required|string|in:rejected,completed,hidden'
    ]);

    if ($validator->fails()) {
      return response()->json([
          'state' => __('Validation failed'),
          'message' => $validator->errors()->first(),
      ], 422);
    }

    try {
      $transaction = Wallet::find($id);
      $transaction->transaction_type = $request->type;
      $transaction->amount = $request->amount;
      $transaction->status = $request->status;
      $transaction->description = $request->description;
      $transaction->save();

      return response()->json([
        'state' => __("Success"),
        'message' => __("Accepted Successful."),
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'state' => __("Error"),
        'message' => $e->getMessage()
      ], 200);
    }
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
        // Check if the password is correct
        // $admin = User::find(Auth::user()->id);
        // if ($admin && Hash::check($request->password, $admin->password)) {

          $transaction = Wallet::find($id);
          $transaction->delete();
          return response()->json([
            'state' => __("Success"),
            'message' => __("Deleted Successfully"),
          ], 200);
      //   } else {
      //     // Password is incorrect
      //     return response()->json([
      //         'status' => 1,
      //         'message' => __('Error'),
      //         'errors' => __('Password Incorrect')
      //     ], 422);
      // }
    } catch (\Exception $e) {
        // Handle any other exceptions
        return response()->json([
            'status' => 1,
            'message' => __('Error'),
            'errors' => $e->getMessage()
        ], 422);
    }

  }

}
