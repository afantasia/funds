<?php

namespace App\Http\Controllers;

use App\Models\NewsFeedModel;
use App\Models\StockHistoryModel;
use Illuminate\Http\Request;
use DB;
class StockController extends Controller
{
    //
    public function makeNews(){
        $newsStringInfo=DB::table("news_words")->inRandomOrder()->first();
        $stockInfo=DB::table("stocks")->inRandomOrder()->first();
        $title=$this->replaceParam($newsStringInfo->title);
        $newsModel=new NewsFeedModel();
        $typeAr=collect(['PLUS',"MINUS","FIXED"])->random();
        $insArray=[
            'stock_id'=>$stockInfo->id,
            'title'=>$title,
            'type'=>$typeAr,
            'limit_percent'=>$typeAr =="FIXED" ? 0 : rand(0,1000)/100,
        ];
        $idx=$newsModel->insertGetId($insArray);
        $this->stockChanger($idx);
        return $idx;
    }
    //
    public function stockChanger($idx){
        $newsData=NewsFeedModel::find($idx);
        //최근 히스토리에 맞추어 인서트해야함
        $historyData=StockHistoryModel::where([["stock_id","=",$newsData->stock_id]])->orderBy("created_at","desc")->first();
        $historyModel=new StockHistoryModel();
        
        if($newsData->type=="PLUS"){
            $nowAmount=$historyData->now_amount + ($historyData->now_amount * (($newsData->limit_percent)/100));
        }elseif($newsData->type=="MINUS"){
            $nowAmount=$historyData->now_amount - ($historyData->now_amount * (($newsData->limit_percent)/100));
        }else{
            $nowAmount=$historyData->now_amount;
        }
        
        $historyModel->insert([
            'stock_id'=>$newsData->stock_id,
            'news_id'=>$idx,
            'before_amount'=>$historyData->now_amount,
            'now_amount'=>$nowAmount,
            'type'=>$newsData->type,
            'limit_percent'=>$newsData->limit_percent,
        ]);
    
    }
}
