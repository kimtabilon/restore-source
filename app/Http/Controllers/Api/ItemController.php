<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\Category;

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
}
