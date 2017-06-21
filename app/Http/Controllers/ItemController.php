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
    	$inventories = ItemStatus::where('slug', $slug)
                ->first()
                ->with([
					'inventories', 
					'inventories.item', 
					'inventories.itemPrices', 
					'inventories.donor',
				])->inventories;  

        // Session::flash('message', [
        //             'title'=>'Alert',
        //             'text'=>'This is a message!',
        //             'type'=>'danger',
        //             'icon'=>'ban'
        //         ]); 
        // dd($data);
    	return view('item.index', ['inventories' => $inventories, 'slug' => $slug]);
    }

    public function angular()
    {
        return view('item.angular');
    }

    public function list($id=false)
    {
        if($id)
            return Item::find($id);
        else
            return Item::all();
    }
}
