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
        $params=[];
        if(auth()->check()){
            $request->type="last";
            $info  = TradeController::getTradeHistory($request);
            $params['now_amount'] = $info['datas']->now_amount;
        }
        return view("layout",$params);
    }
}
