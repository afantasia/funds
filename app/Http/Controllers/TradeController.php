<?php

namespace App\Http\Controllers;

use App\Models\inventoryModel;
use App\Models\StockTradesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    //
    public function getTradeHistory(Request $request)
    {
        $return=['code'=>'9999',"msg"=>'접근권한없음','datas'=>[]];
        if(Auth::check()){
            $model=new StockTradesModel();
            $return['code']="0000";
            $return['datas']=$model->where([['user_id','=',Auth::user()->id]])->orderBy("created_at","desc")->get();
        }
        return $return;
    }
}
