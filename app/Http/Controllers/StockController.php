<?php

namespace App\Http\Controllers;

use App\Models\NewsFeedModel;
use App\Models\StockHistoryModel;
use App\Models\StockModel;
use Dompdf\Image\Cache;
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
        $datas=collect($datas)->each(function($item){
           $model=new StockHistoryModel();
           $item->cost=$model->where("stock_id",$item->id)->orderBy("created_at","desc")->first()->now_amount;
           return $item;
        });

        return $datas;
    }


    //
    public function makeNews(){
        $newsStringInfo=DB::table("news_words")->inRandomOrder()->first();
        $stockInfos=DB::table("stocks")->inRandomOrder()->take(rand(1,5))->get();
        foreach($stockInfos as $stockInfo){
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
        }
        // 영구적으로 캐시 저장
        \Illuminate\Support\Facades\Cache::forever("PUT_KEY", $idx);
        return $idx;
    }
    public function getStockHistory($stockId)
    {
        $interval = isset(request()->interval) ? request()->interval : 300;
        $model=new StockHistoryModel();
        $datas=$model->select(DB::raw("FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(created_at)/{$interval} AS SIGNED)*{$interval}) as created_at,max(now_amount) as max_amount,min(now_amount) as min_amount"))->where("stock_id",$stockId)
        ->groupBy(DB::raw("FROM_UNIXTIME(CAST(UNIX_TIMESTAMP(created_at)/{$interval} AS SIGNED)*{$interval}) "))
        ->orderBy("created_at","desc")->take(500)->get();
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
