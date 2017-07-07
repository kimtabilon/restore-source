<?php

namespace App\Http\Controllers;

use \App\ItemStatus;
use \App\Inventory;
use \App\Item;
use \App\Category;
use Session;

use Illuminate\Http\Request;

class TransactionController extends Controller
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
    	return view('transaction.index');
    }

    public function cashier() 
    {
        return view('transaction.cashier');
    }
}
