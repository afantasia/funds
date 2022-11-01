<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon;
class NewsFeedModel extends Model
{
    protected $casts = [
        'created_at' => "datetime:Y년 m월 d일 H시경",
    ];
    use SoftDeletes;
    protected $table = 'news_feeds';
    
}
