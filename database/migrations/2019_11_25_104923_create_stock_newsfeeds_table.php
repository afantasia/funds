<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockNewsfeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stock_id')->comment('주식아이디');
            $table->string('title')->comment('기사제목');
            $table->string("type")->comment("상승 or 하락 or 고정");
            $table->float('limit_percent',)->comment('주가 상승폭');
            $table->timestamp('created_at')->index()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('생성일자');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('수정일자');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_feeds');
    }
}
