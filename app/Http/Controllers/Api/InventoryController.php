<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\Category;
use \App\Inventory;
use \App\Item;
use \App\ItemStatus;
use \App\ItemCodeType;
use \App\ItemCode;
use \App\ItemDiscount;
use \App\ItemPrice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
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

	public function index($slug)
	{
		return ItemStatus::where('slug', $slug)
				->with([
					'inventories', 
					'inventories.item', 
					'inventories.item.itemCodes', 
					'inventories.item.itemCodes.itemCodeType', 
					'inventories.itemPrice', 
					'inventories.itemDiscount',
					'inventories.item.itemDiscounts',
					'inventories.donor',
				])
				->first()->inventories; 
		
	}

	public function update(Request $request)
	{
		$data = $request->all();

		switch ($data['type']) {
			case 'item':
				$item               = Item::with('itemCodes')->where('id', $data['id'])->first();
				$item->name         = $data['name'];
				$item->description  = $data['description'];
				$item->save();

				return $item;
				break;

			case 'item_code':
				$foundCode = ItemCode::find($data['id']);
				if($foundCode->code != $data['code']) {
					
					$itemCode = new ItemCode();
					$itemCode->code 	= $data['code'];
					$itemCode->item()			->associate($foundCode->item_id);
					$itemCode->itemCodeType()	->associate($foundCode->item_code_type_id);
					$itemCode->save();
				}

				return $itemCode;
				break;	

			case 'item_price':
				$foundPrice = ItemPrice::find($data['id']);
				if($foundPrice->market_price != $data['item_price']) {
					
					$itemPrice = new ItemPrice();
					$itemPrice->market_price 	= $data['item_price'];
					$itemPrice->item()			->associate($foundCode->item_id);
					// $itemPrice->save();
				}

				return $itemPrice;
				break;		
			
			default:
				break;
		}
	}

	public function transfer(Request $request, $status)
	{
		$items = $request->all();
		$inv = [];
		foreach ($items as $item) {
			$inventory = Inventory::find($item['id']);
			$inventory->item_status_id = $status;
			$inventory->save();
			$inv[] = $inventory;
		}
		return $inv;
	}

	public function transferOrCreate(Request $request)
	{
		$data = $request->all();

		switch ($data['action']) {
			case 'change-quantity':
				$left = $data['inventory']['quantity'] - $data['quantity'];
				if($left > 0) {
					$inventory           = Inventory::find($data['inventory']['id']);
					$inventory->quantity = $left;
					$inventory->save();

					$data['inventory']['quantity'] = (int)$data['quantity']; 
					$data['inventory']['remarks']  = $data['remarks']; 
					$inventory 					= new Inventory($data['inventory']);
					$inventory->user()			->associate(Auth::user());
					$inventory->donor()			->associate($data['inventory']['donor_id']);
					$inventory->item()			->associate($data['inventory']['item_id']);
					$inventory->itemDiscount()	->associate($data['inventory']['item_discount_id']);
					$inventory->itemPrice()		->associate($data['inventory']['item_price_id']);
					$inventory->itemStatus()	->associate((int)$data['status']);
					$inventory->itemImage()		->associate($data['inventory']['item_image_id']);
					$inventory->transaction()	->associate($data['inventory']['transaction_id']);
					$inventory->save();
				} 
				else {
					$left=0;
					$inventory           		= Inventory::find($data['inventory']['id']);
					$inventory->item_status_id 	= $data['status'];
					$inventory->remarks 		= $data['remarks'];
					$inventory->save();
				}
				return [ 'quantity' => $left, 'remarks' => $inventory->remarks];
				// return $data;
				break;

			default:
				break;
		}        
	}

	public function statusAndCodeTypes() 
	{
		return [ 'status'=>ItemStatus::all(), 'code_types'=>ItemCodeType::all() ];
	}

	
}
