<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/dashboard');
        /*switch(Auth::user()->role->name) {
            case 'Cashier':
                return redirect('/cashier');
                break;
            default:
                return redirect('/dashboard');
                break;    
        }*/
        // if(Auth::user()->role->name == 'Cashier') {
        //     redirect('/cashier');
        // }
        // dd(Auth::user()->role->name);
        // return view('home');
    }
}
