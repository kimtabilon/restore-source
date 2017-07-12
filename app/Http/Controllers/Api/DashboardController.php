<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\User;
use \App\Item;
use \App\Inventory;
use \App\Donor;
use \App\ItemStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index() 
	{		
		return [ 
			'users' => User::with(['role',])->take(4)->get(),
			'items' => Item::with(['itemCodes',])->take(5)->get(),
			'inventories' => Inventory::with(['item','itemPrices', 'itemImages','itemStatus'])->take(8)->get(),
			'donors' => Donor::with(['profile'])->take(4)->get(),
			'itemStatus' => ItemStatus::with(['inventories','inventories.item'])->get(),
		];				
	}

	public function status()
	{
		return ItemStatus::with(['inventories', 'inventories.item'])->get();
	}
}
