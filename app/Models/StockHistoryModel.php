<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockHistoryModel extends Model
{
    
    use SoftDeletes;
    protected $table = 'stock_historys';
}
