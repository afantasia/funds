<?php

namespace App\Http\Controllers;

use App\Models\inventoryModel;
use App\Models\StockHistoryModel;
use App\Models\StockModel;
use App\Models\StockTradesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    //
    public function getTradeHistory(Request $request)
    {
        $return=['code'=>'9999',"msg"=>'접근권한없음','datas'=>[]];
        if(Auth::check()){
            $model=new StockTradesModel();
            $return['code']="0000";
            if($request->type=='last'){
                $return['datas']=$model->where([['user_id','=',Auth::user()->id]])->orderBy("created_at","desc")->first();
            }else{
                $return['datas']=$model->where([['user_id','=',Auth::user()->id]])->orderBy("created_at","desc")->get();
            }
        }
        return $return;
    }

    public function recentHistory(Request $request)
    {
        $model = new StockHistoryModel();
        $orgDatas = $model
            ->select(
                "stock_historys.now_amount",
                "stock_historys.stock_id",
                DB::raw("DATE_FORMAT(stock_historys.created_at, '%y-%m-%d %H:%i') AS date")
            )
            ->orderBy("created_at", "desc")
            ->limit(100)
            ->get();

        // 데이터를 그룹화
        $groupedDatas = $orgDatas->groupBy('stock_id');

        $stockModel = new StockModel();
        $stockData = $stockModel
            ->whereIn("id", collect($orgDatas)->groupBy("stock_id")->keys()->toArray())
            ->get()
            ->keyBy("id");

        // 전체 날짜 범위 추출 (모든 날짜를 포함한 배열 생성)
        $allDates = $orgDatas->pluck('date')->unique()->sort()->values();

        // Chart.js 포맷으로 변환
        $datas = $groupedDatas->map(function ($items, $stockId) use ($stockData, $allDates) {
            // 정렬된 데이터
            $sortedItems = $items->sortBy('date');

            // 누락된 데이터를 이전 값으로 채우기
            $filledData = $allDates->map(function ($date) use ($sortedItems) {
                static $lastValue = null; // 이전 값을 저장

                // 현재 날짜에 해당하는 데이터가 있으면 값 갱신
                $currentItem = $sortedItems->firstWhere('date', $date);

                if ($currentItem) {
                    $lastValue = (float) $currentItem->now_amount;
                }

                // 현재 날짜와 이전 값을 반환
                return [
                    'x' => $date,
                    'y' => $lastValue,
                ];
            });

            return [
                'label' => $stockData[$stockId]->name,
                'data' => $filledData,
            ];
        })->values();


        return ["code" => "0000", "datas" => $datas];
    }

    public function createBuy(Request $request)
    {
        $return=[];  //example : ["code"=>"0001","message"=>"잘못된 접근입니다","datas"=>[]]
        //1차 스톡모델에서 살수 있는 수량이 있는지 체크
        $stockM=new StockModel();
        $stock=$stockM->find($request->stock_id);
        $stock_count=$stock->stock_count;
        $buyCount=$request->buy_count;
        //현재 재고에서 없으면 재껴야합니다.
        if( $stock_count-$buyCount > 0 ) {
            //현재 주식가격을 가져옵니다
            $stockLogM=new StockHistoryModel();

            $priceData=$stockLogM->where([["stock_id","=",$stock->id]])->orderBy("created_at","desc")->first();
            $buyPrice=$priceData->now_amount * $buyCount;
            $tradeM=new StockTradesModel();
            $userTradeData = $tradeM->where([["user_id","=",auth()->id()]])->orderBy("created_at","desc")->first();
            //자신이 가진 금액이 구매금액보다 높은경우 프로세스 진행
            if($userTradeData->now_amount >= $buyPrice){
                DB::beginTransaction();
                //프로세스 순서
                //1. 인벤토리에 등록한다.
                //2. 금액을 차감한다.
                //3. 주식의 수량을 감소시킨다.
                $inventoryM=new inventoryModel();
                $buyResId=$inventoryM->insertGetId([
                    'user_id'=>auth()->id(),
                    'history_id'=>$priceData->id,
                    'stock_id'=>$stock->id,
                    'amount'=>$buyCount,
                ]);
                if($buyResId > 0){
                    $tradeId = DB::table("stock_trades")->insert([
                        'user_id'=>auth()->id(),
                        'title'=>$stock->name.' 주식 매수',
                        'before_amount'=>$userTradeData->now_amount,
                        'calc_amount'=> $buyPrice * -1,
                        'fee_amount'=>0,
                        'now_amount'=>$userTradeData->now_amount - $buyPrice,
                    ]);
                    if($tradeId > 0){
                        $upResult = $stockM->where("id","=",$stock->id)->decrement("stock_count",$buyCount);
                        if($upResult){
                            DB::commit();
                            $return=["code"=>"0000","message"=>"구매에 성공했습니다","datas"=>[]];
                        }else{
                            DB::rollBack();
                            $return=["code"=>"0006","message"=>"구매에 실패했습니다","datas"=>[]];
                        }
                    }else{
                        DB::rollBack();
                        $return=["code"=>"0005","message"=>"구매에 실패했습니다","datas"=>[]];
                    }
                }else{
                    DB::rollBack();
                    $return=["code"=>"0004","message"=>"구매에 실패했습니다","datas"=>[]];
                }
            }else{
                $return=["code"=>"0003","message"=>"자본금이 부족합니다","datas"=>[]];
            }
        }else{
            $return=["code"=>"0002","message"=>"주식수량이 부족합니다.","datas"=>[]];
        }
        return $return;
    }


    public function getMyInventory(Request $request)
    {
        try {
            $model=new inventoryModel();
            $datas = $model
                ->select("inventories.*",DB::raw("stocks.name as company_name"),DB::raw("stock_historys.now_amount as cost"))
                ->where([[
                "user_id","=",auth()->id()
            ]])->leftJoin("stocks", "stocks.id", "=", "inventories.stock_id")
                ->leftJoin("stock_historys", "stock_historys.id", "=", "inventories.history_id")
                ->orderBy("created_at","desc")->get();
            $datas=collect($datas)->map(function($item){
                $model=new StockHistoryModel();
                $data=$model->where([["stock_id","=",$item->stock_id]])->orderBy("created_at","desc")->first();
                $item->now_cost = $data->now_amount;
                return $item;
            });
            $return=["code"=>"0000","message"=>"데이터 로드 성공","datas"=>$datas];
            return $return;
        }catch (\Exception $e) {
            return ["code"=>"0001","message"=>"실패했습니다","errors"=>$e->getMessage()];
        }
    }
    public function createSell(Request $request)
    {
        $return=[];
        $model=new inventoryModel();
        $stock = $model
                ->select("inventories.*",DB::raw("stocks.name as company_name"))
                ->where([
                    ["inventories.user_id","=",auth()->id()],
                    ["inventories.id","=",$request->inven_id]
                ])->leftJoin("stocks", "stocks.id", "=", "inventories.stock_id")->first();

        if(isset($stock->id)){
            DB::beginTransaction();
            $stockM=new StockModel();
            //프로세스 순서
            //1. 인벤토리내에 소프트딜리트로 제거
            //2. 금액을 증감한다.
            //3. 주식의 수량을 감소시킨다.
            $result = $model->where("id","=",$stock->id)->delete();
            if($result){
                //현재 주식가격을 가져옵니다
                $stockLogM=new StockHistoryModel();
                $priceData=$stockLogM->where([["stock_id","=",$stock->id]])->orderBy("created_at","desc")->first();


                $tradeM=new StockTradesModel();
                $userTradeData = $tradeM->where([["user_id","=",auth()->id()]])->orderBy("created_at","desc")->first();
                $buyPrice=$priceData->now_amount * $stock->amount;
                debug($buyPrice);
                $tradeId = DB::table("stock_trades")->insert([
                    'user_id'=>auth()->id(),
                    'title'=>$stock->company_name.' 주식 매도',
                    'before_amount'=>$userTradeData->now_amount,
                    'calc_amount'=> $buyPrice ,
                    'fee_amount'=>0,
                    'now_amount'=>$userTradeData->now_amount + $buyPrice,
                ]);

                if($tradeId > 0){
                    $upResult = $stockM->where("id","=",$stock->stock_id)->increment("stock_count",$stock->amount);
                    if($upResult){
                        DB::commit();
                        $return=["code"=>"0003","message"=>"매도 완료","datas"=>[]];
                    }else{
                        DB::rollBack();
                        $return=["code"=>"0003","message"=>"매도 실패.","datas"=>[]];
                    }
                }else{
                    DB::rollBack();
                    $return=["code"=>"0002","message"=>"매도 실패.","datas"=>[]];
                }
            }else{
                DB::rollBack();
                $return=["code"=>"0001","message"=>"매도 실패.","datas"=>[]];
            }

        }else{
            $return=["code"=>"0001","message"=>"주식이 없습니다.","datas"=>[]];
        }
        return $return;

    }
}
