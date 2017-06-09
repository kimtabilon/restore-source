<?php

namespace App\Http\Controllers;

use \App\ItemStatus;
use \App\Inventory;
use \App\Item;
use \App\Category;
use Session;

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

    public function index($slug='for-review')
    {
    	$inventories = ItemStatus::where('slug', $slug)->with([
					'inventories', 
					'inventories.item', 
					'inventories.itemPrice', 
					'inventories.donor',
				])->first()->inventories;  

        // Session::flash('message', [
        //             'title'=>'Alert',
        //             'text'=>'This is a message!',
        //             'type'=>'danger',
        //             'icon'=>'ban'
        //         ]); 
        // dd($data);
    	return view('item.index', ['inventories' => $inventories, 'slug' => $slug]);
    }
}
