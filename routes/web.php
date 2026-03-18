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

// 수동 인증 (이메일+비밀번호)
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name("login");
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name("register");
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
});
// 소셜 로그인 (Google) — 진입 경로만 분리, 기존 유지
Route::get('/auth/google', [\App\Http\Controllers\GoogleLoginController::class, 'redirect'])->name("auth.google");
Route::get('/callback', [\App\Http\Controllers\GoogleLoginController::class, 'callback'])->name("callback");
Route::get('/logout', [\App\Http\Controllers\GoogleLoginController::class, 'logout'])->name("logout");
Route::name("stock.")->prefix("/stock/")->group(function(){
    Route::get("getNews",[\App\Http\Controllers\StockController::class,'getNews'])->name("getNews");
    Route::get("getCompany",[\App\Http\Controllers\StockController::class,'getCompany'])->name("getCompany");
    Route::get("getStockHistory/{stockId}",[\App\Http\Controllers\StockController::class,'getStockHistory'])->name("getStockHistory");
    Route::any("recentHistory",[\App\Http\Controllers\TradeController::class,'recentHistory'])->name("recentHistory");
    Route::get("ranking",[\App\Http\Controllers\TradeController::class,'getRanking'])->name("ranking");
});
Route::name("trade.")->prefix("/trade/")->group(function(){
    Route::any("getTradeHistory",[\App\Http\Controllers\TradeController::class,'getTradeHistory'])->name("getTradeHistory");

});
Route::name("user.")->prefix("/user/")->group(function(){
    Route::post("buy",[\App\Http\Controllers\TradeController::class,'createBuy'])->name("buy");
    Route::post("sell",[\App\Http\Controllers\TradeController::class,'createSell'])->name("sell");
    Route::any("inventory",[\App\Http\Controllers\TradeController::class,'getMyInventory'])->name("getMyInventory");
    Route::any("getMyAsset",[\App\Http\Controllers\TradeController::class,'getMyAsset'])->name("getMyAsset");
    Route::post("reset",[\App\Http\Controllers\TradeController::class,'resetAsset'])->name("resetAsset");
});
Route::get('sync',[\App\Http\Controllers\Controller::class,'getCache'])->name("sync");
