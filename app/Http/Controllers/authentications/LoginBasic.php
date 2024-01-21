<?php

namespace App\Http\Controllers\authentications;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }


  public function login(Request $request)
  {
      $credentials = $request->only('email', 'password');

      if (Auth::attempt($credentials, $request->has('remember'))) {
          // Authentication passed
          if (Auth::user()->role === 'admin') {
            return redirect()->route('places-table');
          } else {
            Auth::logout();
            return redirect()->route('auth-login-basic');
          }

      }

      // Authentication failed
      return redirect()->route('auth-login-basic')->with('error', 'Invalid credentials');
  }

  public function logout(){
    Auth::logout();

    return redirect()->route('auth-login-basic');
  }
}
