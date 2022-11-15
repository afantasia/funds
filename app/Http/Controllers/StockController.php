<?php

namespace App\Http\Controllers;

use App\Models\NewsFeedModel;
use App\Models\StockHistoryModel;
use App\Models\StockModel;
use Illuminate\Http\Request;
use DB;
class StockController extends Controller
{
    public function getNews(Request $request){
        $newsModel=new NewsFeedModel();
        $datas=$newsModel->leftJoin('stocks', function($join) {
            $join->on('news_feeds.stock_id', '=', 'stocks.id');
        })->select("news_feeds.*","stocks.name")->orderBy("news_feeds.created_at","desc")->take(10)->get();
        return $datas;
    }
    
    public function getCompany(Request $request){
        $stockModel=new StockModel();
        $datas=$stockModel->get();
        return $datas;
    }
    
    
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
    public function getStockHistory($stockId)
    {
        $model=new StockHistoryModel();
        $datas=$model->where("stock_id",$stockId)->orderBy("created_at","desc")->get();
        return $datas;
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
