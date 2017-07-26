<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\User;
use \App\Item;
use \App\Inventory;
use \App\Donor;
use \App\ItemStatus;
use \App\Transaction;
use \Carbon\Carbon;

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

	public function index($from='', $to='') 
	{	
		$from = Carbon::parse($from);
		$to   = Carbon::parse($to)->addDay();

		// return $to;
		return [ 
			'users' 		=> User::whereBetween('created_at', [$from, $to])->with(['role',])->take(4)->get(),
			'items' 		=> Item::whereBetween('created_at', [$from, $to])->orderBy('created_at')->take(5)->get(),
			'inventories' 	=> Inventory::whereBetween('created_at', [$from, $to])->with(['item','itemPrices', 'itemImages','itemStatus'])->take(8)->get(),
			'donors' 		=> Donor::whereBetween('created_at', [$from, $to])->orderBy('created_at')->with(['profile'])->take(4)->get(),
			'itemStatus' 	=> ItemStatus::whereBetween('created_at', [$from, $to])->with(['inventories','inventories.item'])->get(),
			'transactions' 	=> Transaction::whereBetween('created_at', [$from, $to])->orderBy('created_at')->with(['inventories','inventories.item'])->get(),
		];				
	}

	public function status()
	{
		return ItemStatus::with(['inventories', 'inventories.item'])->get();
	}
}
