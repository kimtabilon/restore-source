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
use \App\Donor;

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
					'inventories.itemPrices', 
					'inventories.itemImages', 
					'inventories.itemDiscounts',
					'inventories.donors',
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
					
					$itemCode 					= new ItemCode();
					$itemCode->code 			= $data['code'];
					$itemCode->item()			->associate($foundCode->item_id);
					$itemCode->itemCodeType()	->associate($foundCode->item_code_type_id);
					$itemCode->save();
				}

				return $itemCode;
				break;	

			case 'item_price':
				$price 		= ItemPrice::find($data['market_price_id']);
				$inventory 	= Inventory::find($data['id']);
				if($price->market_price != $data['market_price']) {
					$newPrice = new ItemPrice();
					$newPrice->market_price = $data['market_price'];
					$newPrice->save();

					$inventory->itemPrices()->attach($newPrice);
				}
				return $inventory;
				break;	

			case 'modify_category':
				$category               = Category::where('id', $data['id'])->first();
				$category->name         = $data['name'];
				$category->description  = $data['description'];
				$category->save();

				return $category;
				break;	

			case 'new_item':
				$newCategory = false;

				if($data['category_id']==0) {
					$category 				= new Category();
					$category->name 		= $data['category'];
					$category->description	= 'Edit text';
					$category->save();
					$newCategory = true;
				}
				else {
					$category = $data['category_id'];
				}
				
				$item 				= new Item();
				$item->name 		= $data['name'];
				$item->description 	= $data['description'];
				$item->category()	->associate($category);
				$item->save();

				$barcode 				= new ItemCode();
				$barcode->code 			= $data['code'];
				$barcode->item() 		->associate($item);
				$barcode->itemCodeType()->associate($data['code_type']);
				$barcode->save();

				return [ 'item'=>$item, 'code'=>$barcode, 'new_category'=>$newCategory, 'category'=>$category ];
				break;		
			
			default:
				break;
		}
	}

	public function transfer(Request $request, $status)
	{
		$items 			= $request->all();
		$inventories 	= [];

		foreach ($items as $item) {
			$inventory 		= Inventory::find($item['id']);
			$inventory 		->item_status_id = $status;
			$inventory 		->save();

			$inventories[] 	= $inventory;
		}
		return $inventories;
	}

	public function transferOrCreate(Request $request)
	{
		$data = $request->all();

		switch ($data['type']) {
			case 'transfer_or_create':
				$left = $data['inventory']['quantity'] - $data['quantity'];
				if($left > 0) {
					$inv           = Inventory::find($data['inventory']['id']);
					$inv->quantity = $left;
					$inv->save();

					$discounts 		= $inv->itemDiscounts;
					$images 		= $inv->itemImages;
					$prices 		= $inv->itemPrices;
					$donors 		= $inv->donors;
					$transactions 	= $inv->transactions;

					$data['inventory']['quantity'] = (int)$data['quantity']; 
					$data['inventory']['remarks']  = $data['remarks']; 
					$inventory 					= new Inventory($data['inventory']);
					$inventory->user()			->associate(Auth::user());
					$inventory->item()			->associate($data['inventory']['item_id']);
					$inventory->itemStatus()	->associate((int)$data['status']);
					$inventory->save();

					if($discounts->count()) {
						foreach($discounts as $v) { $inventory->itemDiscounts()->attach($v); }
					}
					if($images->count()) 		{ $inventory->itemImages()	->attach($images->last()); }
					if($prices->count()) 		{ $inventory->itemPrices()	->attach($prices->last()); }
					if($donors->count()) 		{ $inventory->donors()		->attach($donors->last()); }
					if($transactions->count()) 	{ $inventory->transactions()->attach($transactions->last()); }
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
