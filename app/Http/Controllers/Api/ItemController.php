<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\Category;
use \App\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
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
		return Category::with(['items', 'items.itemCodes'])
				->get(); 	
	}	

	public function destroy(Request $request) 
	{
		$item = $request->input('id');
		Item::find($item)->delete();
		return 'Removed!';
	}
}
