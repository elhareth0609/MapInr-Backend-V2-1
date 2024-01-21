<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Analytics extends Controller
{
  public function index()
  {
    return redirect()->route('places-table');
    return view('content.dashboard.dashboards-analytics');
  }
}
