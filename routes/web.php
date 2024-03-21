<?php

use App\Http\Controllers\authentications\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\MiscError;
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
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // name utk slug ny menu

// Supplier
Route::get('/master/supplier', [SupplierController::class, 'index'])->name('master-supplier');
Route::get('/master/supplier/get-data', [SupplierController::class, 'getData'])->name('master-supplier.getData');
Route::get('/master/supplier/create', [SupplierController::class, 'create'])->name('master-supplier.detail');
Route::post('/master/supplier/store', [SupplierController::class, 'store'])->name('master-supplier.store');
Route::get('/master/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('master-supplier.edit');
Route::put('/master/supplier/{id}/update', [SupplierController::class, 'update'])->name('master-supplier.update');
Route::delete('/master/supplier/{id}/delete', [SupplierController::class, 'delete'])->name('master-supplier.delete');

// Customer
Route::get('/master/customer', [CustomerController::class, 'index'])->name('master-customer');

// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// pages
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
