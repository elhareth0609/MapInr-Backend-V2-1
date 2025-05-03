<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BillController;

use App\Http\Controllers\CounterController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;


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
  Route::post('/v1/counters/search', [CounterController::class, 'search'])->name('counter.search');
  Route::get('/v1/user/all', [AuthController::class, 'all'])->name('user.all');

  Route::post('/v1/wallets/create', [WalletController::class, 'create'])->name('wallet.create');
  Route::post('/v1/wallets/create-lot', [WalletController::class, 'create_lot'])->name('wallet.create.lot');
  Route::get('/v1/wallets/get', [WalletController::class, 'get'])->name('wallet.get');


  Route::post('/v1/reactions/create', [ReactionController::class, 'create'])->name('reactions.create');
  Route::post('/v1/reactions/create-lot', [ReactionController::class, 'create_lot'])->name('reactions.create.lot');
  Route::get('/v1/reactions/get', [ReactionController::class, 'get'])->name('reactions.get');
  Route::post('/v1/reactions/filter', [ReactionController::class, 'filter'])->name('reactions.filter');

  Route::get('/v1/phones/all', [PhoneController::class, 'all'])->name('phones.all');
  Route::post('/v1/phones/create', [PhoneController::class, 'create'])->name('phones.create');
  Route::post('/v1/phones/create-lot', [PhoneController::class, 'create_lot'])->name('phones.create.lot');

  Route::get('/v1/bills/all', [BillController::class, 'all'])->name('bills.all');
  Route::post('/v1/bills/create', [BillController::class, 'create'])->name('bills.create');
  Route::post('/v1/bills/create-lot', [BillController::class, 'create_lot'])->name('bills.create.lot');
});
