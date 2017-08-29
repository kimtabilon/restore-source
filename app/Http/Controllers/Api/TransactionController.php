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
use \App\ItemCode;
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
				$paymentTypes = PaymentType::whereIn('name', ['Cash', 'Credit', 'Debit', 'Internal Sale'])->pluck('name');
				break;
			
			default:
				$paymentTypes = PaymentType::pluck('name');
				break;
		}

		return PaymentType::with([
								'transactions.inventories', 
								'transactions.inventories.item',
								'transactions.inventories.donors',
								'transactions.inventories.donors.profile',
								'transactions.inventories.itemPrices',
								'transactions.inventories.itemSellingPrices',
								'transactions.inventories.itemStatus',
								'transactions.inventories.itemCodes',
								'transactions.inventories.itemDiscounts'
							])
							->whereIn('name', $paymentTypes)
							->get();	
		
	}

	public function data() 
	{
		$item_status = ItemStatus::with(['inventories'])->orderBy('name')->get();
		$good_item 	 = $item_status->where('name', 'Good')->first()->id;
		return [
			'categories'	=> Category::orderBy('name')->get(),
			'code_types'	=> ItemCodeType::orderBy('name')->get(),
			'donors' 		=> Donor::with(['profile', 'donorType', 'inventories', 'storeCredits'])->orderBy('given_name')->get(),
			'donor_types'	=> DonorType::orderBy('name')->get(),
			'item_status' 	=> $item_status,
			'items' 		=> Item::with(['category'])->orderBy('name')->get(),
			'inventories'	=> Inventory::where('item_status_id', $good_item)
										->with(['item', 'itemStatus', 'itemImages', 'itemCodes', 'itemPrices', 'itemSellingPrices', 'itemDiscounts'])
										->get(),
		];
	}	

	public function inventories($status)
	{
		return Inventory::where('item_status_id', $status)
						->with(['item', 'itemStatus', 'itemImages', 'itemCodes', 'itemPrices', 'itemSellingPrices'])
						->get();
	}

	public function checkCode($type, $code)
	{
		switch ($type) {
			case 'RS':
				return ItemCode::where('code',$code)->get();
				break;

			case 'DA':
			case 'C' :
				return Transaction::where('da_number', $code)->get();
				break;	
			
			default:
				# code...
				break;
		}
	}

	public function create(Request $request)
	{
		$payment 		= $request->input('payment');
		$donor 			= $request->input('donor');
		$found_donor	= Donor::find($donor['id']);
		$inventories 	= $request->input('items');
		$da_no   		= $request->input('da_no');
		$special_discount= $request->input('special_discount');
		$remarks   		= $request->input('remarks');

		$status = ItemStatus::all();
		foreach ($status as $s) {
			${snake_case($s->name)} = $s->id;
		}

		$new_transaction = new Transaction();
		$new_transaction->da_number 		= $da_no;
		$new_transaction->special_discount 	= $special_discount;
		$new_transaction->remarks 			= $remarks;
		$new_transaction->paymentType()->associate($payment['id']);
		$new_transaction->save();

		if($payment['name']=='Item Donation') {
			foreach ($inventories as $inventory) {
				$match = Inventory::where('item_id', $inventory['item_id'])->get()->last();

				$new_inv 				= new Inventory();
				$new_inv->quantity 		= $inventory['quantity'];
				$new_inv->unit 			= $inventory['unit'];
				$new_inv->remarks   	= $inventory['remarks'];
				$new_inv->itemStatus()  ->associate($inventory['item_status_id']);
				$new_inv->item() 		->associate($inventory['item_id']);
				$new_inv->user() 		->associate(Auth::user());
				$new_inv->save();

				/* ITEM PRICE */
				$new_market_price  = $inventory['item_prices'][0]['market_price'];
				$new_selling_price = $inventory['item_selling_prices'][0]['market_price'];
				
				$find_market_price  = ItemPrice::where('market_price', $new_market_price)->get();
				$find_selling_price = ItemPrice::where('market_price', $new_selling_price)->get();

				if($find_selling_price->count() != 0) {
					$selling_price = $find_selling_price->first();
				}
				else {
					$selling_price = new ItemPrice();
					$selling_price->market_price = $new_selling_price;
					$selling_price->save();
				}

				if($find_market_price->count() != 0) {
					$new_price = $find_market_price->first();
				}
				else {
					$new_price = new ItemPrice();
					$new_price->market_price = $new_market_price;
					$new_price->save();
				}
				$new_inv->itemPrices()  		->attach($new_price);
				$new_inv->itemSellingPrices()  	->attach($selling_price);
				/* end of ITEM PRICE */
				
				if($match) {
					$discounts 		= $match->itemDiscounts;
					$codes 			= $match->itemCodes;
					$images 		= $match->itemImages;
					$refImages 		= $match->itemRefImages;
					$prices 		= $match->itemPrices;
					$sellingPrices 	= $match->itemSellingPrices;
					$donors 		= $match->donors;
					$transactions 	= $match->transactions;

					if($discounts 	->count()) { foreach($discounts  	as $v) { $new_inv->itemDiscounts()	->attach($v); } }
					if($transactions->count()) { foreach($transactions  as $v) { $new_inv->transactions()	->attach($v); } }
					if($donors 		->count()) { foreach($donors        as $v) { $new_inv->donors()			->attach($v); } }
					
					if($codes 			->count()){ $new_inv->itemCodes()			->attach($codes 		->last()); }
					if($images 			->count()){ $new_inv->itemImages()			->attach($images 		->last()); }
					if($refImages 		->count()){ $new_inv->itemRefImages()		->attach($refImages 	->last()); }
					if($prices 			->count()){ $new_inv->itemPrices()			->attach($prices 		->last()); }
					if($sellingPrices 	->count()){ $new_inv->itemSellingPrices()	->attach($sellingPrices ->last()); }
				}

				$new_inv->donors()				->attach($found_donor);
				$new_inv->transactions()		->attach($new_transaction);	

				/* ITEM CODE */
				$new_code  				= $inventory['item_codes'][0]['code'];
				$code_type 				= $inventory['item_codes'][0]['item_code_type_id'];
				
				$new_item_code 			= new ItemCode();
				$new_item_code	->code 	= $new_code;
				$new_item_code	->itemCodeType()->associate($code_type);
				$new_item_code	->save();
				$new_inv		->itemCodes()	->attach($new_item_code);
				/* end of ITEM CODE */			
			}	 
		}
		else {
			$new_status = $payment['name']=='Internal Sale'|| $payment['name']=='Warehouse Transfer' ? $for_transfer : $sold;

			foreach ($inventories as $inventory) {
				$id = $inventory['id'];
				if($id > 0) {
					$match = Inventory::find($id);
					$left  = $match->quantity - $inventory['quantity'];

					/*$discounts 		= $match->itemDiscounts;
					$images 		= $match->itemImages;
					$codes 			= $match->itemCodes;
					$prices 		= $match->itemPrices;
					$selling_prices = $match->itemSellingPrices;*/

					$discounts 		= $match->itemDiscounts;
					$codes 			= $match->itemCodes;
					$images 		= $match->itemImages;
					$refImages 		= $match->itemRefImages;
					$prices 		= $match->itemPrices;
					$sellingPrices 	= $match->itemSellingPrices;
					$donors 		= $match->donors;
					$transactions 	= $match->transactions;
					
					if($left > 0) {
						$match->quantity = $left;
						$match->save();

						$new_inv 				= new Inventory();
						$new_inv->quantity 		= $inventory['quantity'];
						$new_inv->remarks   	= $inventory['remarks'];
						$new_inv->unit   		= $inventory['unit'];
						$new_inv->itemStatus()  ->associate($new_status);
						$new_inv->item() 		->associate($inventory['item_id']);
						$new_inv->user() 		->associate(Auth::user());
						$new_inv->save();

						if($discounts 	->count()) { foreach($discounts  	as $v) { $new_inv->itemDiscounts()	->attach($v); } }
						if($transactions->count()) { foreach($transactions  as $v) { $new_inv->transactions()	->attach($v); } }
						if($donors 		->count()) { foreach($donors        as $v) { $new_inv->donors()			->attach($v); } }
						
						if($codes 			->count()){ $new_inv->itemCodes()			->attach($codes 		->last()); }
						if($images 			->count()){ $new_inv->itemImages()			->attach($images 		->last()); }
						if($refImages 		->count()){ $new_inv->itemRefImages()		->attach($refImages 	->last()); }
						if($prices 			->count()){ $new_inv->itemPrices()			->attach($prices 		->last()); }
						if($sellingPrices 	->count()){ $new_inv->itemSellingPrices()	->attach($sellingPrices ->last()); }
						
						$new_inv->donors()		->attach($found_donor);
						$new_inv->transactions()->attach($new_transaction); 
					}
					else {
						$match->item_status_id = $new_status;
						$match->save();

						$match->transactions()->attach($new_transaction); 
					}
				}
			}
		}


		return Transaction::where('id', $new_transaction->id)
							->with([
								'inventories', 
								'inventories.item',
								'inventories.donors',
								'inventories.itemCodes',
								'inventories.itemPrices',
								'inventories.itemSellingPrices',
								'inventories.itemStatus', 
								'paymentType'
							])
							->get()->first();
	}

	
}
