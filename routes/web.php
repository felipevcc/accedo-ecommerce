<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [CategoryController::class, 'home'])->name('categories.home');

Route::group(['middleware' => ['auth']], function () {

	// View after being authenticated
	Route::get('/home', [HomeController::class, 'index'])->name('home');

	// Get authenticated user
	Route::get('/authUser', [AuthController::class, 'profile'])->name('auth.profile');

	// Roles
	Route::group(['prefix' => 'roles', 'middleware' => ['role:admin'], 'controller' => RoleController::class], function () {
		Route::get('/getAll', 'getAll')->name('roles.getAll');
	});

	// Users
	Route::group(['prefix' => 'users', 'middleware' => ['role:admin'], 'controller' => UserController::class], function () {
		Route::get('/', 'index')->name('users.index');
		Route::get('/getAllDT', 'getAllDT')->name('users.getAllDT');
		Route::get('/{user}', 'show')->name('users.show');
		Route::post('/', 'store')->name('users.store');
		Route::put('/{user}', 'update')->name('users.update');
		Route::delete('/{user}', 'destroy')->name('users.destroy');
	});

	// Products
	Route::group(['prefix' => 'products', 'middleware' => ['role:seller|admin'], 'controller' => ProductController::class], function () {
		Route::get('/', 'index')->name('products.index');
		Route::get('/getAllDT', 'getAllDT')->name('products.getAllDT');
		Route::post('/store', 'store')->name('products.store');
		Route::post('/update/{product}', 'update')->name('products.update');
		Route::delete('/{product}', 'destroy')->name('products.destroy');
	});

	// Categories
	Route::group(['prefix' => 'categories', 'middleware' => ['role:seller|admin'], 'controller' => CategoryController::class], function () {
		Route::get('/', 'index')->name('categories.index');
		Route::get('/getAllDT', 'getAllDT')->name('categories.getAllDT');
		Route::get('/{category}', 'show')->name('categories.show');
		Route::post('/', 'store')->name('categories.store');
		Route::put('/{category}', 'update')->name('categories.update');
		Route::delete('/{category}', 'destroy')->name('categories.destroy');
	});

	// Carts
	Route::group(['prefix' => 'carts', 'middleware' => ['role:user'], 'controller' => CartController::class], function () {
		Route::get('/{user}', 'show')->name('carts.show');
		/* Route::post('/store', 'store')->name('carts.store'); */
	});

	// CartDetails
	Route::group(['prefix' => 'cartDetails', 'middleware' => ['role:user'], 'controller' => CartDetailController::class], function () {
		Route::post('/store', 'store')->name('cartDetails.store');
		Route::put('/{cartDetail}', 'update')->name('cartDetails.update');
		Route::get('/{cartDetail}', 'show')->name('cartDetails.show');
		Route::delete('/{cartDetail}', 'destroy')->name('cartDetails.destroy');
	});
});

// Get all products in a category
Route::get('/categories/{category}/products', [CategoryController::class, 'loadProducts'])->name('categories.products');

Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
	Route::get('/search', 'search')->name('products.search');
	Route::get('/{product}', 'show')->name('products.show');
});
