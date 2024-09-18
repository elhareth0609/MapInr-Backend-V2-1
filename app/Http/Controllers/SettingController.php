<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    //
  public function settings(){
    $admin = User::find(Auth::user()->id);
    return view('dashboard.settings.index')
    ->with('admin', $admin);
  }

  public function change_password(Request $request) {

    try {
        $validator = Validator::make($request->all(), [
          'past_password' => 'required|string',
          'new_password' => 'required|string|min:8',
          'confirm_new_password' => 'required|string|min:8|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => __('Validation failed'),
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        $admin = User::find(Auth::user()->id);
        if ($admin && Hash::check($request->past_password, $admin->password)) {
          $admin->password = Hash::make($request->input('new_password'));
          $admin->save();
        } else {
          return response()->json([
            'status' => 1,
            'message' => __('Error'),
            'errors' => __('Past Password Incorrect')
          ], 422);
          }

        return response()->json([
          'state' => __('Success'),
          'message' => __('Changed successfully')
        ]);

      } catch (\Exception $e) {
        return response()->json([
          'status' => 1,
          'message' => __('Error'),
          'errors' => $e->getMessage()
        ], 422);
      }

  }

  public function update_information(Request $request) {

    $rules = [
      'fullname' => 'required|string',
      'email' => 'sometimes|email|nullable',
      'phone' => 'sometimes|nullable|string',
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

      // Check if validation fails
    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => __('Validation failed'),
        'error' => $validator->errors()->first(),
      ], 422);
    }
    try {
    $user = User::find(Auth::user()->id);
    if ($user) {
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return response()->json([
          'icon' => 'success',
          'state' => __('Success'),
          'message' => __('User updated successfully.'),
        ]);
    } else {
        return response()->json([
          'icon' => 'alert',
          'state' => __('Sorry,'),
          'message' => __('There Is No User To Update.'),
        ]);

    }
    } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'error' => $e->getMessage(),
        ],401);
    }
  }
}
