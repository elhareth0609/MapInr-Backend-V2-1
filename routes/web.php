<?php

use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\DataTablesController;
use App\Http\Controllers\ExelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;







// Main Page Route
Route::get('/', [HomeController::class, 'index'])->name('home-page');
Route::get('/ddd', [HomeController::class, 'ddd'])->name('delte-everything');

Route::middleware(['guest'])->group(function () {
  Route::post('/auth/login', [LoginBasic::class, 'login'])->name('auth-login');
  Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
});




Route::middleware(['auth'])->group(function () {

  Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

  Route::get('/auth/logout', [LoginBasic::class, 'logout'])->name('auth-logout');

  Route::get('/settings', [SettingController::class, 'settings'])->name('settings');
  Route::post('/change-password', [SettingController::class, 'change_password'])->name('settings.change.password');
  Route::post('/update-information', [SettingController::class, 'update_information'])->name('settings.update.information');


  Route::get('/users', [DataTablesController::class, 'users'])->name('users');
  Route::get('/worker-places/{id}', [DataTablesController::class, 'worker_places'])->name('worker-places-table');
  Route::get('/worker-counters/{id}', [DataTablesController::class, 'worker_counters'])->name('worker-counters-table');
  Route::get('/worker-phones/{id}', [DataTablesController::class, 'worker_phones'])->name('worker-phones-table');

  Route::get('/places', [DataTablesController::class, 'places'])->name('places-table');
  Route::get('/place-workers/{id}', [DataTablesController::class, 'place_workers'])->name('place-workers-table');
  Route::get('/place-counters/{id}', [DataTablesController::class, 'place_counters'])->name('place-counters-table');
  Route::get('/place-copied/{id}', [DataTablesController::class, 'place_copied'])->name('place-copied-table');

  Route::get('/counters', [DataTablesController::class, 'counters'])->name('counters-table');
  Route::get('/phones', [DataTablesController::class, 'phones'])->name('phones-table');
  Route::get('/wallets', [DataTablesController::class, 'wallets'])->name('wallets-table');
  Route::get('/bills', [DataTablesController::class, 'bills'])->name('bills-table');


  Route::get('/municipalitys', [DataTablesController::class, 'municipalitys'])->name('municipalitys');
  Route::get('/municipality/{id}/places', [DataTablesController::class, 'municipality_places'])->name('municipality.places');

  Route::get('/place/{id}', [PlaceController::class, 'place'])->name('place');
  Route::get('/place/{id}/workers', [PlaceController::class, 'place_workers'])->name('place_users');
  Route::get('/place/{id}/counters', [PlaceController::class, 'place_counters'])->name('place_counters');
  Route::get('/place/{id}/copied', [PlaceController::class, 'place_copied'])->name('place_copied');
  Route::delete('/place/destroy/{id}', [PlaceController::class, 'destroy'])->name('place.destroy');
  Route::get('place/remove-counter/{id}/{counterId}', [PlaceController::class, 'removeCounterPlace'])->name('place-remove-place-counter');


  Route::get('/user/{id}', [UserController::class, 'user'])->name('user');
  Route::get('/user/{id}/places', [UserController::class, 'user_places'])->name('user_places');
  Route::get('/user/{id}/counters', [UserController::class, 'user_counters'])->name('user_counters');
  Route::get('/user/{id}/transitions', [DataTablesController::class, 'user_transitions'])->name('user_transitions');
  Route::get('/user/{id}/reactions', [DataTablesController::class, 'user_reactions'])->name('user_reactions');
  Route::get('/user/{id}/phones', [DataTablesController::class, 'worker_phones'])->name('user_phones');

  Route::post('/user/create', [UserController::class, 'create'])->name('user-create');
  Route::post('/user/update', [UserController::class, 'update'])->name('user-update');
  Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('user-destroy');
  Route::delete('/user/logout/{id}', [UserController::class, 'logout'])->name('user-logout');

  Route::post('/generate-password', [UserController::class, 'generate_password'])->name('generate-password');
  Route::post('/user/add-place-worker', [UserController::class, 'addPlaceWorker'])->name('add.place.worker');
  Route::post('/user/add-worker-place', [UserController::class, 'addWorkerPlace'])->name('add.worker.place');
  Route::post('/user/add-counter-worker', [UserController::class, 'addCounterWorker'])->name('add.counter.worker');

  Route::get('user/remove-place/{id}/{placeId}', [UserController::class, 'removePlaceWorker'])->name('user-remove-place-worker');
  Route::get('user/remove-counter/{id}/{counterId}', [UserController::class, 'removeCounterWorker'])->name('user-remove-counter-worker');
  Route::post('user/remove-worker-counters', [UserController::class, 'removeWorkerCounters'])->name('user.delete.counters.all');

  Route::post('/upload-file-tranactions', [ExelController::class, 'uploadFileTranactions'])->name('upload.file.tranactions');
  Route::get('/exoprt-file-tranactions', [ExelController::class, 'exportFileTranactions'])->name('export.file.tranactions');

  Route::post('/upload-file', [ExelController::class, 'uploadFile'])->name('upload.file');
  Route::get('/exoprt-file/{id}', [ExelController::class, 'exportFile'])->name('export.file'); // for place counters
  Route::get('/exoprt-user-file/{id}', [ExelController::class, 'exportUserFile'])->name('export.user.file'); // for place counters
  Route::get('/exoprt-file-zip/{id}', [ExelController::class, 'exportFileZip'])->name('export.file.zip');
  Route::get('/exoprt-municipalitys-zip', [ExelController::class, 'exportMunicipalitysZip'])->name('export.municipalitys.zip'); // for all

  Route::post('/counters/delete-all', [CounterController::class, 'delete_all'])->name('counter.delete.all');

  Route::post('/municipality/create', [MunicipalityController::class, 'create'])->name('municipality.create');
  Route::post('/municipality/update', [MunicipalityController::class, 'update'])->name('municipality.update');
  Route::delete('/municipality/destroy/{id}', [MunicipalityController::class, 'destroy'])->name('municipality.destroy');
  Route::get('/municipality/{id}', [MunicipalityController::class, 'municipality'])->name('municipality');

  Route::post('/check-password', [UserController::class, 'check_password'])->name('password.check');



  Route::post('wallets/{id}/submit', [WalletController::class, 'submit'])->name('wallet.submit');
  Route::delete('wallets/{id}/delete', [WalletController::class, 'delete'])->name('wallet.delete');
  Route::post('transitions/add', [WalletController::class, 'add'])->name('transitions.add');
  Route::delete('/wallet/unupload/{id}', [WalletController::class, 'unuploadFile'])->name('product.unupload.file');

  Route::delete('reactions/{id}/delete', [ReactionController::class, 'delete'])->name('reactions.delete');
  // Route::post('reactions/add', [ReactionController::class, 'add'])->name('reactions.add');

  Route::post('user/remove-worker-transitions', [UserController::class, 'removeWorkerTransitions'])->name('user.delete.transitions.all');
  Route::post('remove-all-transitions', [UserController::class, 'removeallTransitions'])->name('transitions.delete.all');



  Route::post('/phones/save-phone-counter', [PhoneController::class, 'savePhoneCounter'])->name('counter.save.phone.counter'); // for counter in phones table
  Route::post('/phones/save-audio-value', [PhoneController::class, 'saveAudioValue'])->name('phone.save.audio.value'); // for audio in counters table
  Route::delete('phones/{id}/delete', [PhoneController::class, 'delete'])->name('phone.delete');
  Route::post('remove-all-phones', [PhoneController::class, 'deleteAll'])->name('phone.delete.all');


  Route::post('/counters/save-audio-number', [CounterController::class, 'saveAudioNumber'])->name('counter.save.audio.number'); // for audio in counters table
  Route::post('/counters/save-counter-phone', [CounterController::class, 'saveCounterPhone'])->name('counter.save.counter.phone'); // for phone in counters table
  Route::post('/counters/save-counter-place', [CounterController::class, 'saveCounterPlace'])->name('counter.save.counter.place');


  Route::delete('bills/{id}/delete', [BillController::class, 'delete'])->name('bill.delete');
  Route::post('remove-all-bills', [BillController::class, 'deleteAll'])->name('bill.delete.all');


  Route::get('switch-language/{locale}', [LanguageController::class, 'switchLanguage'])->name('switch.language');


});
