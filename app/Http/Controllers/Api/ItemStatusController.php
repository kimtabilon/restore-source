<?php

namespace App\Http\Controllers\Api;

use \App\ItemStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemStatusController extends Controller
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
    	return ItemStatus::all(); 
    	
    }
    
}
