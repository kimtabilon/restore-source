<?php

namespace App\Http\Controllers\Api;

use \App\ItemStatus;
use \App\Inventory;
use \App\Item;
use \App\Category;
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
					'inventories.itemPrice', 
					'inventories.donor',
				])
                ->first()->inventories; 
    	
    }

    public function update(Request $request)
    {
        // return $request->input('id');
        $data = $request->all();

        switch ($data['type']) {
            case 'item':
                $item               = Item::with('itemCodes')->where('id', $data['id'])->first();
                $item->name         = $data['name'];
                $item->description  = $data['description'];
                $item->save();

                return $item;
                break;
            
            default:
                break;
        }
        // return $data;
    }

    public function transfer(Request $request, $status)
    {
        $items = $request->all();
        $inv = [];
        // return $items;
        foreach ($items as $item) {
            $inventory = Inventory::find($item['id']);
            $inventory->item_status_id = $status;
            $inventory->save();
            $inv[] = $inventory;
        }
        return $inv;
        // return $request->input('status');
        // return $status;
    }

    
}
