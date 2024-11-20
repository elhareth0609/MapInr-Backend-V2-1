<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index(){
    return redirect()->route('dashboard-analytics');
  }

  public function ddd(){
      try {
        // Clear various caches
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return redirect()->route('home-page')->with('success', 'Caches cleared successfully.');
    } catch (\Exception $e) {
        // Handle any exceptions if the cache clearing fails
        return redirect()->route('home-page')->with('error', 'Failed to clear caches. ' . $e->getMessage());
    }
  }
}
