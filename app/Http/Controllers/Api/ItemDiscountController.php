<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\ItemDiscount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemDiscountController extends Controller
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
		return ItemDiscount::with('inventories','inventories.item','inventories.itemStatus')->orderBy('type')->get();	
	}

	public function save(Request $request)
	{
		$discount = $request->all();

		switch ($discount['action']) {
			case 'new':
				$new_discount =  new ItemDiscount($discount);
				$new_discount->user()->associate(Auth::user());
				$new_discount->save();

				return ItemDiscount::where('id',$new_discount->id)->with('inventories','inventories.item','inventories.itemStatus')->first();
				break;

			case 'edit':
				$match =  ItemDiscount::find($discount['id']);
				$match->percent 	= $discount['percent'];
				$match->type 		= $discount['type'];
				$match->remarks 	= $discount['remarks'];
				$match->start_date 	= $discount['start_date'];
				$match->end_date 	= $discount['end_date'];
				$match->user_id 	= Auth::user()->id;

				$match->save();

				return ItemDiscount::where('id',$match->id)->with('inventories','inventories.item','inventories.itemStatus')->first();
				break;	
			
			default:
				break;
		}
	}

	public function destroy(Request $request)
	{
		$discount = ItemDiscount::find($request->all()['id']);
		$discount->delete();

		return $discount;
	}
}
