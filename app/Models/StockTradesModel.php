<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTradesModel extends Model
{
    use SoftDeletes;
    protected $table = 'stock_trades';
    public function defaultCharge($userId)
    {
        $count=$this->where([["user_id","=",$userId]])->count();
        if($count==0){
            $this->insert([
                'user_id' => $userId,
                'title' => "초기 비용 자금 투척",
                'before_amount' => 0,
                'calc_amount' => env('DEFAULT_STOCK_CASH', 10000000),
                'fee_amount' => 0,
                'now_amount' => env('DEFAULT_STOCK_CASH', 10000000),
            ]);
        }

        return;
    }
}
