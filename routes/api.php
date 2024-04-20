<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\WalletController;

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
  Route::post('/v1/counters/share', [CounterController::class, 'share'])->name('counter.share');
  Route::get('/v1/user/all', [AuthController::class, 'all'])->name('user.all');

  Route::post('/v1/wallets/create', [WalletController::class, 'create'])->name('wallet.create');
  Route::post('/v1/wallets/create-lot', [WalletController::class, 'create_lot'])->name('wallet.create.lot');
  Route::get('/v1/wallets/get', [WalletController::class, 'get'])->name('wallet.get');

});
