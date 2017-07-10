<?php

namespace App\Http\Controllers;

use Auth;
use Image;

use Illuminate\Http\Request;

class ItemImageController extends Controller
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
        return view('item.image');
    }
}
