<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\pages\CustomerController;
use App\Http\Controllers\pages\SupplierController;

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

// Main Page Route
//----------------
// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // name utk slug ny menu

// Supplier
Route::get('/supplier', [SupplierController::class, 'index'])->name('master-supplier');
Route::get('/supplier/get-data', [SupplierController::class, 'getData'])->name('supplier.getData');
Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.detail');
Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
Route::put('/supplier/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');
Route::delete('/supplier/{id}/delete', [SupplierController::class, 'delete'])->name('supplier.delete');

// Customer
Route::get('/customer', [CustomerController::class, 'index'])->name('customer');

// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// pages
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
