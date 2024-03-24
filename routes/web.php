<?php

use App\Http\Controllers\authentications\LoginController;
use App\Http\Controllers\authentications\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\RegisterController;
use App\Http\Controllers\pages\CustomerController;
use App\Http\Controllers\pages\SupplierController;
use App\Http\Controllers\pages\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth'])->group(function () {
  // Main Page Route
  //----------------
  // Dashboard
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // name utk slug ny menu

  // Master
  Route::prefix('/master')->group(function () {
    // Supplier
    Route::prefix('/supplier')->controller(SupplierController::class)->name('master-supplier.')->group(function () {
      Route::get('/', 'index')->name('index');                  // '/master/supplier/'
      Route::get('/get-data', 'getData')->name('getData');      // '/master/supplier/get-data'
      Route::get('/create', 'create')->name('create');          // '/master/supplier/create'
      Route::post('/store', 'store')->name('store');            // '/master/supplier/store'
      Route::get('/{id}/edit', 'edit')->name('edit');           // '/master/supplier/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');     // '/master/supplier/{id}/update'
      Route::delete('/{id}/delete', 'delete')->name('delete');  // '/master/supplier/{id}/delete'
    });
    
    // Customer
    Route::prefix('/customer')->controller(CustomerController::class)->name('master-customer.')->group(function () {
      Route::get('/', 'index')->name('index');                  // '/master/customer/'
      Route::get('/get-data', 'getData')->name('getData');      // '/master/customer/get-data'
      Route::get('/create', 'create')->name('create');          // '/master/customer/create'
      Route::post('/store', 'store')->name('store');            // '/master/customer/store'
      Route::get('/{id}/edit', 'edit')->name('edit');           // '/master/customer/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');     // '/master/customer/{id}/update'
      Route::delete('/{id}/delete', 'delete')->name('delete');  // '/master/customer/{id}/delete'
    });
  });

  // Settings
  Route::prefix('/settings')->group(function () {
    // User
    Route::prefix('/user')->controller(UserController::class)->name('settings-user.')->group(function () {
      Route::get('/', 'index')->name('index');                  // '/settings/user/'
      Route::get('/get-data', 'getData')->name('getData');      // '/settings/user/get-data'
      Route::get('/create', 'create')->name('create');          // '/settings/user/create'
      Route::post('/store', 'store')->name('store');            // '/settings/user/store'
      Route::get('/{id}/edit', 'edit')->name('edit');           // '/settings/user/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');     // '/settings/user/{id}/update'
      Route::post('/{id}/change-password', 'changePassword')->name('changePassword');  // '/settings/user/{id}/delete'
    });
  });

  // // locale
  // Route::get('lang/{locale}', [LanguageController::class, 'swap']);
});

// Page Error or Not Found
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LogoutController::class, 'index'])->name('logout');
