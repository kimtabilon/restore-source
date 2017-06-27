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

Route::get('items', 'ItemController@index');

Route::get('angular', 'ItemController@angular');
Route::get('apiV2/items/{id?}', 'ItemController@list');

Route::get('inventories/{status?}', 'InventoryController@index');

Route::group(['prefix' => 'api/v1'], function () {
	Route::get('inventories/{status?}', 'Api\InventoryController@index');
	Route::post('inventories/update', 'Api\InventoryController@update');
	Route::post('inventories/transferOrCreate', 'Api\InventoryController@transferOrCreate');
	Route::post('inventories/transfer/{status}', 'Api\InventoryController@transfer');

	Route::get('item-status-and-code-types', 'Api\InventoryController@statusAndCodeTypes');

	Route::get('items', 'Api\ItemController@index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


