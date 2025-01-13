<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\users.nick_name::factory(10)->create();
        $wordAr=[
            ['title'=>'{stocks.name} 임직원 A씨 자택에서 숨쉰채 발견'],
            ['title'=>'투자자 {users.nick_name} 말하기를 {stocks.name}사 주식이 오를거라고 예상'],
            ['title'=>'{stocks.name}사 주식 {stocks.name}와 병합 M&A 계약 추진중'],
            ['title'=>'{users.nick_name}, {stocks.name}사 주식 풀매수 움직임 보여..'],
            ['title'=>'{users.nick_name}, {stocks.name}사 주식 풀매도 움직임 보여..'],
            ['title'=>'{users.nick_name}, {stocks.name}사 사옥 앞에서 소리벗고 빤스질러'],
            ['title'=>'{users.nick_name}, {stocks.name}사 직원 연봉이 오른다'],
            ['title'=>'{stocks.name}사 직원 집에서 졸다 보너스 지급받아 논란..'],
            ['title'=>'{stocks.name}사 직원 오늘은 치킨이닭'],
            ['title'=>'{users.nick_name} 대주주 총회에서 비키니차림으로 등장하여 논란'],
            ['title'=>'{stocks.name}사 총수 탑신병에 걸려 응급실행'],
            ['title'=>'{stocks.name}사 직원 A씨와 {users.nick_name} 주식 마포대교에서 팬티바람으로 티배깅댄스 추다 현장검거'],
            ['title'=>'{stocks.name}사 총수, 탕비실 커피믹스횡령 논란..'],
            ['title'=>'{stocks.name}사 총수,겨울배가 맛있다고 해 논란..'],
            ['title'=>'{users.nick_name}, 왜 나에대한  기준이 엄격하냐고 소리질러 논란'],
            ['title'=>'{stocks.name} 임직원, 탕수육 부먹논란 점화되어 대국민사과'],
            ['title'=>'{stocks.name} 임직원, 민트초코에 대한 트윗을 남겨 논란 재점화'],
        ];
        DB::table("news_words")->insert($wordAr);

        $stockAr=[
            ['name'=>'삼만전자','content'=>'삼만원이 돈이냐','stock_count'=>1000],
            ['name'=>'JOOGLE','content'=>'구글아님 죽을임','stock_count'=>1000],
            ['name'=>'스택 오빠플로우','content'=>'오빠 순서도 좀 그려줭','stock_count'=>1000],
            ['name'=>'공장초기화','content'=>'세상을 공장초기화한다!','stock_count'=>1000],
            ['name'=>'넹이뻐','content'=>'실은 뻥이요를 더 좋아합니다.','stock_count'=>1000],
            ['name'=>'즐택스','content'=>'즐!이나 먹으라지!','stock_count'=>1000],
        ];
        foreach($stockAr as $v){
            $stock_id=DB::table("stocks")->insertGetId($v);
            $price=rand(120,400)*100;
            DB::table("stock_historys")->insertGetId([
                'stock_id'=>$stock_id,
                'before_amount'=>$price,
                'now_amount'=>$price,
                'type'=>"START",
                'limit_percent'=>0,
            ]);
        }


        //DB::table("stock_historys")->insert($stockAr);
    }
}
