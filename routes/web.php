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

Route::get('dashboard', 'DashboardController@index');

Route::get('items', 'ItemController@index');
Route::get('items/images', 'ItemImageController@index');
Route::get('items/discounts', 'ItemDiscountController@index');
Route::get('inventories', 'InventoryController@index');
Route::get('transactions', 'TransactionController@index');
Route::get('cashier', 'TransactionController@cashier');
Route::get('users', 'UserController@index');
Route::get('profile', 'UserController@profile');
Route::get('donors', 'DonorController@index');

Route::get('angular', 'ItemController@angular');
Route::get('apiV2/items/{id?}', 'ItemController@list');

Route::group(['prefix' => 'api/v1'], function () {
	Route::get('dashboard', 'Api\DashboardController@index');
	Route::get('dashboard/status', 'Api\DashboardController@status');

	Route::get('inventories/{status?}', 'Api\InventoryController@index');
	Route::post('inventories/update', 'Api\InventoryController@update');
	Route::post('inventories/transferOrCreate', 'Api\InventoryController@transferOrCreate');
	Route::post('inventories/transfer/{status}', 'Api\InventoryController@transfer');
	Route::post('inventories/add-image', 'Api\InventoryController@addImage');

	Route::get('item-status-and-code-types', 'Api\InventoryController@statusAndCodeTypes');

	Route::get('items', 'Api\ItemController@index');
	Route::post('items/destroy', 'Api\ItemController@destroy');
	Route::post('items/category/destroy', 'Api\ItemController@destroyCategory');

	Route::get('transactions', 'Api\TransactionController@index');
	Route::post('transactions/create', 'Api\TransactionController@create');
	Route::get('transactions/data', 'Api\TransactionController@data');
	Route::get('transactions/inventories/{id}', 'Api\TransactionController@inventories');

	Route::get('donors', 'Api\DonorController@index');
	Route::post('donors/create', 'Api\DonorController@create');
	Route::post('donors/destroy', 'Api\DonorController@destroy');

	Route::get('item-images/', 'Api\ItemImageController@index');
	Route::post('item-images/create', 'Api\ItemImageController@create');
	Route::post('item-images/destroy', 'Api\ItemImageController@destroy');

	Route::get('item-discounts', 'Api\ItemDiscountController@index');
	Route::post('item-discounts/save', 'Api\ItemDiscountController@save');
	Route::post('item-discounts/destroy', 'Api\ItemDiscountController@destroy');
	Route::post('item-discounts/add', 'Api\ItemDiscountController@add');
	Route::post('item-discounts/remove', 'Api\ItemDiscountController@remove');

	Route::get('users', 'Api\UserController@index');
	Route::get('users/profile', 'Api\UserController@profile');
	Route::post('users/save', 'Api\UserController@save');
	Route::post('users/destroy', 'Api\UserController@destroy');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


