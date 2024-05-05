<?php

use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\DataTablesController;
use App\Http\Controllers\ExelController;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\icons\MdiIcons;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;






// Main Page Route
Route::get('/', [HomeController::class, 'index'])->name('home-page');
Route::get('/ddd', [HomeController::class, 'ddd'])->name('delte-everything');

// authentication page
// Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
// Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// authentication action
// Route::post('/auth/register', [RegisterBasic::class, 'register'])->name('auth-register');
// Route::post('/auth/forgot-password', [ForgotPasswordBasic::class, 'forgot_password'])->name('auth-reset-password');

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

Route::get('/places', [DataTablesController::class, 'places'])->name('places-table');
Route::get('/place-workers/{id}', [DataTablesController::class, 'place_workers'])->name('place-workers-table');
Route::get('/place-counters/{id}', [DataTablesController::class, 'place_counters'])->name('place-counters-table');

Route::get('/counters', [DataTablesController::class, 'counters'])->name('counters-table');

Route::get('/wallets', [DataTablesController::class, 'wallets'])->name('wallets-table');


Route::get('/municipalitys', [DataTablesController::class, 'municipalitys'])->name('municipalitys');
Route::get('/municipality/{id}/places', [DataTablesController::class, 'municipality_places'])->name('municipality.places');

Route::get('/place/{id}', [PlaceController::class, 'place'])->name('place');
Route::get('/place/{id}/workers', [PlaceController::class, 'place_workers'])->name('place_users');
Route::get('/place/{id}/counters', [PlaceController::class, 'place_counters'])->name('place_counters');
Route::delete('/place/destroy/{id}', [PlaceController::class, 'destroy'])->name('place.destroy');


Route::get('/user/{id}', [UserController::class, 'user'])->name('user');
Route::get('/user/{id}/places', [UserController::class, 'user_places'])->name('user_places');
Route::get('/user/{id}/counters', [UserController::class, 'user_counters'])->name('user_counters');
Route::get('/user/{id}/transitions', [DataTablesController::class, 'user_transitions'])->name('user_transitions');
Route::post('/user/create', [UserController::class, 'create'])->name('user-create');
Route::post('/user/update', [UserController::class, 'update'])->name('user-update');
Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('user-destroy');
Route::post('/generate-password', [UserController::class, 'generate_password'])->name('generate-password');
Route::post('/user/add-place-worker', [UserController::class, 'addPlaceWorker'])->name('add.place.worker');
Route::post('/user/add-worker-place', [UserController::class, 'addWorkerPlace'])->name('add.worker.place');
Route::post('/user/add-counter-worker', [UserController::class, 'addCounterWorker'])->name('add.counter.worker');

Route::get('user/remove-place/{id}/{placeId}', [UserController::class, 'removePlaceWorker'])->name('user-remove-place-worker');
Route::get('user/remove-counter/{id}/{counterId}', [UserController::class, 'removeCounterWorker'])->name('user-remove-counter-worker');
Route::post('user/remove-worker-counters', [UserController::class, 'removeWorkerCounters'])->name('user.delete.counters.all');


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



Route::get('wallets/{id}/reject', [WalletController::class, 'reject'])->name('wallet.reject');
Route::post('wallets/{id}/accept', [WalletController::class, 'accept'])->name('wallet.accept');
Route::delete('wallets/{id}/delete', [WalletController::class, 'delete'])->name('wallet.delete');
Route::get('wallets/{id}/hide', [WalletController::class, 'hide'])->name('wallet.hide');
Route::post('transitions/add', [WalletController::class, 'add'])->name('transitions.add');















//   // layout
// Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
// Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
// Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
// Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
// Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// // pages
// Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
// Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
// Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
// Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
// Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// // cards
// Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// // User Interface
// Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
// Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
// Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
// Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
// Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
// Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
// Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
// Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
// Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
// Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
// Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
// Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
// Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
// Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
// Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
// Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
// Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
// Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
// Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// // extended ui
// Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
// Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// // icons
// Route::get('/icons/icons-mdi', [MdiIcons::class, 'index'])->name('icons-mdi');

// // form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// // form layouts
// Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
// Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// // tables
// Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');


Route::get('switch-language/{locale}', [LanguageController::class, 'switchLanguage'])->name('switch.language');


});
