<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('products', [ProductController::class, 'index'])->middleware('auth');
Route::post('store-product', [ProductController::class, 'store'])->middleware('auth');
Route::post('edit-product', [ProductController::class, 'edit'])->middleware('auth');
Route::post('delete-product', [ProductController::class, 'destroy'])->middleware('auth');

Route::get('categories', [CategoryController::class, 'index'])->name('category.index')->middleware('auth');
Route::post('category/store', [CategoryController::class, 'store'])->name('category.store')->middleware('auth');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
