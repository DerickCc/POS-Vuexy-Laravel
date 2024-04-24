<?php

use App\Http\Controllers\authentications\LoginController;
use App\Http\Controllers\authentications\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\RegisterController;
use App\Http\Controllers\pages\master\CustomerController;
use App\Http\Controllers\pages\master\SupplierController;
use App\Http\Controllers\pages\inventory\ProductController;
use App\Http\Controllers\pages\settings\UserController;
use App\Http\Controllers\pages\transaction\PurchaseOrderController;
use App\Http\Controllers\pages\transaction\SalesOrderController;

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
      Route::get('/', 'index')->name('index');                                      // '/master/supplier/'
      Route::get('/browse-supplier', 'browseSupplier')->name('browse-supplier');    // '/master/supplier/browse-supplier'
      Route::get('/get-supplier-list', 'getSupplierList')->name('get-supplier-list');    // '/master/supplier/get-supplier-list'
      Route::get('/get-supplier-by-id', 'getSupplierById')->name('get-supplier-by-id');    // '/master/supplier/get-supplier-by-id'
      Route::get('/create', 'create')->name('create');                              // '/master/supplier/create'
      Route::post('/store', 'store')->name('store');                                // '/master/supplier/store'
      Route::get('/{id}/edit', 'edit')->name('edit');                               // '/master/supplier/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');                         // '/master/supplier/{id}/update'
      Route::delete('/{id}/delete', 'delete')->name('delete');                      // '/master/supplier/{id}/delete'
    });

    // Customer
    Route::prefix('/customer')->controller(CustomerController::class)->name('master-customer.')->group(function () {
      Route::get('/', 'index')->name('index');                                      // '/master/customer/'
      Route::get('/browse-customer', 'browseCustomer')->name('browse-customer');    // '/master/customer/browse-customer'
      Route::get('/get-customer-list', 'getCustomerList')->name('get-customer-list');    // '/master/customer/get-customer-list'
      Route::get('/create', 'create')->name('create');                              // '/master/customer/create'
      Route::post('/store', 'store')->name('store');                                // '/master/customer/store'
      Route::get('/{id}/edit', 'edit')->name('edit');                               // '/master/customer/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');                         // '/master/customer/{id}/update'
      Route::delete('/{id}/delete', 'delete')->name('delete');                      // '/master/customer/{id}/delete'
    });
  });

  // Inventory
  Route::prefix('/inventory')->group(function () {
    // Product
    Route::prefix('/product')->controller(ProductController::class)->name('inventory-product.')->group(function () {
      Route::get('/', 'index')->name('index');                                    // '/inventory/product/'
      Route::get('/browse-product', 'browseProduct')->name('browse-product');     // '/inventory/product/browse-product'
      Route::get('/get-product-list', 'getProductList')->name('get-product-list'); // '/inventory/product/get-product-list'
      Route::get('/get-product-by-id', 'getProductById')->name('get-product-by-id'); // '/inventory/product/get-product-by-id'
      Route::get('/get-product-stock', 'getProductStock')->name('get-product-stock'); // '/inventory/product/get-product-stock'
      Route::get('/create', 'create')->name('create');                            // '/inventory/product/create'
      Route::post('/store', 'store')->name('store');                              // '/inventory/product/store'
      Route::get('/{id}/edit', 'edit')->name('edit');                             // '/inventory/product/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');                       // '/inventory/product/{id}/update'
      Route::delete('/{id}/delete', 'delete')->name('delete');                    // '/inventory/product/{id}/delete'
    });
  });

  // Transaction
  Route::prefix('/transaction')->group(function () {
    // Purchase Order
    Route::prefix('/purchase-order')->controller(PurchaseOrderController::class)->name('transaction-purchase-order.')->group(function () {
      Route::get('/', 'index')->name('index');                    // '/transaction/purchase-order/'
      Route::get('/browse-po', 'browsePo')->name('browse-po');    // '/transaction/purchase-order/browse-po'
      Route::get('/create', 'create')->name('create');            // '/transaction/purchase-order/create'
      Route::post('/store', 'store')->name('store');              // '/transaction/purchase-order/store'
      Route::get('/{id}/view', 'view')->name('view');             // '/transaction/purchase-order/{id}/view'
      Route::get('/{id}/edit', 'edit')->name('edit');             // '/transaction/purchase-order/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');       // '/transaction/purchase-order/{id}/update'
      Route::put('/{id}/finish', 'finish')->name('finish');       // '/transaction/purchase-order/{id}/finish'
      Route::delete('/{id}/delete', 'delete')->name('delete');    // '/transaction/purchase-order/{id}/delete'
    });

    // Sales Order
    Route::prefix('/sales-order')->controller(SalesOrderController::class)->name('transaction-sales-order.')->group(function () {
      Route::get('/', 'index')->name('index');                    // '/transaction/sales-order/'
      Route::get('/browse-so', 'browseSo')->name('browse-so');    // '/transaction/sales-order/browse-so'
      Route::get('/create', 'create')->name('create');            // '/transaction/sales-order/create'
      Route::post('/store', 'store')->name('store');              // '/transaction/sales-order/store'
      Route::get('/{id}/view', 'view')->name('view');             // '/transaction/sales-order/{id}/edit'
      Route::put('/{id}/update-paid-amount', 'updatePaidAmount')->name('update-paid-amount');       // '/transaction/sales-order/{id}/update-paid-amount'
      Route::put('/{id}/cancel', 'cancel')->name('cancel');    // '/transaction/sales-order/{id}/cancel'
    });
  });

  // Settings
  Route::prefix('/settings')->group(function () {
    // User
    Route::prefix('/user')->controller(UserController::class)->name('settings-user.')->group(function () {
      Route::get('/', 'index')->name('index');                          // '/settings/user/'
      Route::get('/browse-user', 'browseUser')->name('browse-user');    // '/settings/user/browse-user'
      Route::get('/create', 'create')->name('create');                  // '/settings/user/create'
      Route::post('/store', 'store')->name('store');                    // '/settings/user/store'
      Route::get('/{id}/edit', 'edit')->name('edit');                   // '/settings/user/{id}/edit'
      Route::put('/{id}/update', 'update')->name('update');             // '/settings/user/{id}/update'
      Route::put('/{id}/change-account-status', 'changeAccountStatus')->name('change-account-status');  // '/settings/user/{id}/change-account-status'
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
