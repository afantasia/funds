<?php

namespace App\Http\Controllers;

use http\Env\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function replaceParam($string){
        preg_match_all('/\{([^{}]+)\}/',$string,$tmpParseAr);
        $strAr=[];
        foreach($tmpParseAr[0] as $paramName){
            $strAr[str_replace(["{","}"],"",$paramName)][]=$paramName;
        }
        foreach($strAr as $k=>$v) {
            $tk=explode(".",$k);
            $table=$tk[0];
            $column=$tk[1];
            $limit=count($v);
            $dataAr=DB::table($table)->select($column)->inRandomOrder()->limit($limit)->get();
            if(count($dataAr)){
                foreach ($dataAr as $k1=>$v2){
                    $string=str_replace_once("{".$k."}",$v2->$column,$string);
                }
                //다돌고나서도 없으면 지워주는게 맞을듯
                $string=str_replace("{".$k."}","",$string);
            }else{
                $string=str_replace("{".$k."}","익명 A",$string);
            }
        }
        return $string;
    }

    public function getRouteLists()
    {

        $pathArray=[];
        $routeLists = \Route::getRoutes()->get();
        foreach($routeLists as $k=>$route){

            if(isset($route->action['as']) && collect($route->action)->contains(['web'])  ){
                $pathArray[$route->action['as']]=["methods"=>$route->methods[0],"uri"=>$route->uri];
            }
            //debug(collect($route)->toArray());

        }

        return $pathArray;
    }

    public function getCache(){
        $cacheKey = "PUT_KEY"; // 저장된 캐시 키
        $cachedValue = Cache::get($cacheKey); // 캐시 값 가져오기
        if ($cachedValue === null) {
            $cachedValue = 0;
        }
        return response()->json([
            'status' => 'success',
            'data' => $cachedValue,
        ]);
    }
}
