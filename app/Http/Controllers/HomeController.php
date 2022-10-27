<?php

namespace App\Http\Controllers;

use App\Models\StockTradesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        dump(Auth::check());
        dump(Auth::id());
        
        
        
        
        
        
        
        
        return view("welcome");
    }
}
