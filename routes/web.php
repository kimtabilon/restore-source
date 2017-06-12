<?php

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

Route::get('items/{id?}', 'ItemController@index');

Route::get('angular', 'ItemController@angular');
Route::get('apiV2/items/{id?}', 'ItemController@list');

Route::get('inventories/{status?}', 'InventoryController@index');
Route::group(['prefix' => 'api/v1'], function () {
	Route::get('inventories/{status?}', 'Api\InventoryController@index');
	Route::post('inventories', 'Api\InventoryController@update');
	Route::post('inventories/transfer/{status}', 'Api\InventoryController@transfer');

	Route::get('item-status', 'Api\ItemStatusController@index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


