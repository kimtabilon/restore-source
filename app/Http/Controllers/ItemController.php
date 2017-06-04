<?php

namespace App\Http\Controllers;

use \App\ItemStatus;
use \App\Inventory;
use \App\Item;
use \App\Category;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index($id=1)
    {
    	$data = ItemStatus::with([
    											'inventories', 
    											'inventories.item', 
    											'inventories.itemPrice', 
    											'inventories.donor',
    										])->get();  

    	return view('item.index', ['status' => $data, 'id' => $id]);
    }
}
