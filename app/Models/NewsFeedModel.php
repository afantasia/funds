<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsFeedModel extends Model
{
    
    use SoftDeletes;
    protected $table = 'news_feeds';
    
    
}
