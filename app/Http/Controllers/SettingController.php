<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;

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

        $admin = User::find(Auth::user()->id);
        $admin->password = $request->password;
        $admin->save();

        return response()->json([
          'status' => 1,
          'message' => 'Changed successfully'
        ]);

      } catch (\Exception $e) {
        return response()->json([
          'status' => 1,
          'message' => $e->getMessage()
        ]);
      }

  }

  public function update_information(Request $request) {

    try {
        $admin = User::find(Auth::user()->id);
        $admin->fullname = $request->fullname;
        $admin->email = $request->email;
        $admin->password = $request->password;
        $admin->save();

        return response()->json([
            'status' => 1,
            'message' => 'Updated successfully'
        ]);

    } catch (\Exception $e) {
      return response()->json([
        'status' => 1,
        'message' => $e->getMessage()
      ]);
    }
  }
}
