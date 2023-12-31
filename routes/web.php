<?php

use Illuminate\Support\Facades\Route;

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

Route::post('orders', 'OrderController@store')->name('orders.store');
Route::get('orders/created', 'OrderController@index')->name('orders.index');
Route::get('orders/process', 'OrderController@process')->name('orders.process');
Route::get('plates', 'PlateController@index')->name('plates.index');
Route::get('ingredients', 'IngredientController@index')->name('ingredients.index');
Route::get('purchases', 'PurchaseController@index')->name('purchases.index');

