<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\DataTablesController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/v1/user/login', [AuthController::class, 'login'])->name('login-api');

Route::middleware(['bearer.token','auth:sanctum'])->group(function () {
  Route::get('/v1/places/all', [PlaceController::class, 'all_places'])->name('all-places');
  Route::post('/v1/counters/create', [CounterController::class, 'create'])->name('counter.create');
  Route::post('/v1/counters/create-lot', [CounterController::class, 'create_lot'])->name('counter.create.lot');
  Route::post('/v1/counters/destroy', [CounterController::class, 'destroy'])->name('counter.destroy');
  Route::post('/v1/counters/destroy-lot', [CounterController::class, 'destroy_lot'])->name('counter.destroy.lot');
  Route::post('/v1/counters/update', [CounterController::class, 'update'])->name('counter.update');
  Route::post('/v1/counters/update-lot', [CounterController::class, 'update_lot'])->name('counter.update.lot');

});
