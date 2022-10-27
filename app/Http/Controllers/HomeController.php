<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        dump(Auth::check());
        $a=DB::table("news_words")->inRandomOrder()->first();
        $aa=$this->replaceParam($a->title);
        
        
        
        
        
        
        
        
        
        
        return view("welcome");
    }
}
