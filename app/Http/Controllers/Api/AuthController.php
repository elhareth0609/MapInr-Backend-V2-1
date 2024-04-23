<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function login(Request $request)
  {
      // Validate the request
      $validator = Validator::make($request->all(), [
        'code' => 'required|string'
      ]);

      if ($validator->fails()) {
          return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]);
      }

      try {
          $user = User::where('password', '=', $request->code)->first();

          if ($user) {
              $user->update([
                'password' => null
              ]);

              $token = $user->createToken('auth_token')->plainTextToken;

              return response()->json([
                'status' => 1,
                'token' => $token
              ]);
          } else {
              return response()->json([
                'status' => 0,
                'message' => 'There Is No User With This Code'
              ]);
          }
      } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]);
      }
  }

  public function all(Request $request) {

    try {
      $workers = User::select('id','fullname','phone','role')
                      ->where('role','worker')
                      ->whereNotIn('id', [$request->user()->id])
                      ->get();

      foreach ($workers as $worker) {
        unset($worker->role);
      }

      return response()->json([
          'status' => 1,
          'workers' => $workers
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);
    }
  }
}
