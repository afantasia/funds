<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class,'index'])->name("home");
Route::get('/login', [\App\Http\Controllers\GoogleLoginController::class, 'redirect'])->name("login");
Route::get('/logout', [\App\Http\Controllers\GoogleLoginController::class, 'logout'])->name("logout");
Route::get('/callback', [\App\Http\Controllers\GoogleLoginController::class, 'callback'])->name("callback");
Route::name("stock.")->prefix("/stock/")->group(function(){
    Route::get("getNews",[\App\Http\Controllers\StockController::class,'getNews'])->name("getNews");
    Route::get("getCompany",[\App\Http\Controllers\StockController::class,'getCompany'])->name("getCompany");
    Route::get("getStockHistory/{stockId}",[\App\Http\Controllers\StockController::class,'getStockHistory'])->name("getStockHistory");
});
Route::name("trade.")->prefix("/trade/")->group(function(){
    Route::any("getTradeHistory",[\App\Http\Controllers\TradeController::class,'getTradeHistory'])->name("getTradeHistory");
});
Route::name("user.")->prefix("/user/")->group(function(){
    Route::post("buy",[\App\Http\Controllers\TradeController::class,'createBuy'])->name("buy");
    Route::post("sell",[\App\Http\Controllers\TradeController::class,'createSell'])->name("sell");
    Route::any("inventory",[\App\Http\Controllers\TradeController::class,'getMyInventory'])->name("getMyInventory");
});
Route::get('sync',[\App\Http\Controllers\Controller::class,'getCache'])->name("sync");
