<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\Transaction;
use \App\Donor;
use \App\DonorType;
use \App\PaymentType;
use \App\ItemStatus;
use \App\ItemPrice;
use \App\Item;
use \App\Inventory;
use \App\ItemCodeType;
use \App\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
		switch (Auth::user()->role->name) {
			case 'Cashier':
				$paymentTypes = PaymentType::whereIn('name', ['Cash', 'Credit', 'Debit'])->pluck('id');
				break;
			
			default:
				$paymentTypes = PaymentType::pluck('id');
				break;
		}

		return Transaction::with([
								'inventories', 
								'inventories.item',
								'inventories.donors',
								'inventories.itemPrices',
								'inventories.itemStatus', 
								'paymentType'
							])
							->whereIn('payment_type_id', $paymentTypes)
							->get();	
		
	}

	public function data() 
	{
		$item_status = ItemStatus::with(['inventories'])->orderBy('name')->get();
		$good_item 	 = $item_status->where('name', 'Good')->first()->id;
		return [
			'categories'	=> Category::orderBy('name')->get(),
			'code_types'	=> ItemCodeType::orderBy('name')->get(),
			'donors' 		=> Donor::with(['profile', 'donorType'])->orderBy('given_name')->get(),
			'donor_types'	=> DonorType::orderBy('name')->get(),
			'payment_types'	=> Auth::user()->role->name=='Cashier' ? PaymentType::whereIn('name', ['Cash','Debit','Credit'])->orderBy('name')->get() : PaymentType::orderBy('name')->get(),
			'item_status' 	=> $item_status,
			'items' 		=> Item::with(['category', 'itemCodes'])->orderBy('name')->get(),
			'inventories'	=> Inventory::where('item_status_id', $good_item)
										->with(['item', 'itemStatus', 'itemImages'])
										->get(),
		];
	}	

	public function inventories($status)
	{
		return Inventory::where('item_status_id', $status)->with(['item', 'itemStatus', 'itemImages'])->get();
	}

	public function create(Request $request)
	{
		$payment 		= $request->input('payment');
		$donor 			= $request->input('donor');
		$found_donor	= Donor::find($donor['id']);
		$inventories 	= $request->input('items');
		$da_no   		= $request->input('da_no');
		$dt_no   		= $request->input('dt_no');

		$status = ItemStatus::all();
		foreach ($status as $s) {
			${snake_case($s->name)} = $s->id;
		}

		$new_transaction = new Transaction();
		$new_transaction->da_number = $da_no;
		$new_transaction->dt_number = $dt_no;
		$new_transaction->paymentType()->associate($payment['id']);
		$new_transaction->save();

		if(
			$payment['name']=='Cash' || 
			$payment['name']=='Credit' || 
			$payment['name']=='Debit' ||
			$payment['name']=='Internal Transfer'
			) 
		{
			$status = $payment['name']=='Internal Transfer' ? $for_transfer : $sold;

			foreach ($inventories as $inventory) {
				$id = $inventory['id'];
				if($id > 0) {
					$match = Inventory::find($id);
					$left  = $match->quantity - $inventory['quantity'];

					$discounts 		= $match->itemDiscounts;
					$images 		= $match->itemImages;
					$prices 		= $match->itemPrices;
					
					if($left > 0) {
						$match->quantity = $left;
						$match->save();

						$new_inv 				= new Inventory();
						$new_inv->quantity 		= $inventory['quantity'];
						$new_inv->remarks   	= $inventory['remarks'];
						$new_inv->itemStatus()  ->associate($status);
						$new_inv->item() 		->associate($inventory['item_id']);
						$new_inv->user() 		->associate(Auth::user());
						$new_inv->save();

						if($discounts->count()) {
							foreach($discounts as $v) { $new_inv->itemDiscounts()->attach($v); }
						}
						if($images->count()) 		{ $new_inv->itemImages()->attach($images->last()); }
						if($prices->count()) 		{ $new_inv->itemPrices()->attach($prices->last()); }
						
						$new_inv->donors()		->attach($found_donor);
						$new_inv->transactions()->attach($new_transaction); 
					}
					else {
						$match->item_status_id = $status;
						$match->save();

						$match->transactions()->attach($new_transaction); 
					}
				}
				else {
					
				}
			}	
		}

		if($payment['name']=='Item Donation') {
			foreach ($inventories as $inventory) {
				$match = Inventory::where('item_id', $inventory['item_id'])->get()->last();

				$new_inv 				= new Inventory();
				$new_inv->quantity 		= $inventory['quantity'];
				$new_inv->remarks   	= $inventory['remarks'];
				$new_inv->itemStatus()  ->associate($inventory['item_status_id']);
				$new_inv->item() 		->associate($inventory['item_id']);
				$new_inv->user() 		->associate(Auth::user());
				$new_inv->save();

				$new_price = new ItemPrice();
				$new_price->market_price = $inventory['market_price'];
				$new_price->save();
				
				$new_inv->itemPrices()  ->attach($new_price);
				$new_inv->donors()		->attach($found_donor);
				$new_inv->transactions()->attach($new_transaction);
				
				if($match) {

					$discounts 		= $match->itemDiscounts;
					$images 		= $match->itemImages;
					// $prices 		= $match->itemPrices;

					if($discounts->count()) {
						foreach($discounts as $v) { $new_inv->itemDiscounts()->attach($v); }
					}
					if($images->count()){ $new_inv->itemImages()->attach($images->last()); }
					// if($prices->count()){ $new_inv->itemPrices()->attach($prices->last()); }
				}				
			}	 
		}


		return Transaction::where('id', $new_transaction->id)
							->with([
								'inventories', 
								'inventories.item',
								'inventories.donors',
								'inventories.itemPrices',
								'inventories.itemStatus', 
								'paymentType'
							])
							->get()->first();
	}
}
