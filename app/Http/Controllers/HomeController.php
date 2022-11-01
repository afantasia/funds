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
        $d=$this->getRouteLists();
        return view("layout");
    }
}
