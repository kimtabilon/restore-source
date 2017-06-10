<?php

namespace App\Http\Controllers\Api;

use \App\ItemStatus;
use \App\Inventory;
use \App\Item;
use \App\Category;
use App\Http\Controllers\Controller;
use Session;

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
					'inventories.itemPrice', 
					'inventories.donor',
				])
                ->first()->inventories; 


        // Session::flash('message', [
        //             'title'=>'Alert',
        //             'text'=>'This is a message!',
        //             'type'=>'danger',
        //             'icon'=>'ban'
        //         ]); 
        // dd($data);
    	
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
