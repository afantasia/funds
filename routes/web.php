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
Route::post('/', [\App\Http\Controllers\Controller::class,'getRouteLists'])->name("getLists");

Route::get('/login', [\App\Http\Controllers\GoogleLoginController::class, 'redirect'])->name("login");
Route::get('/logout', [\App\Http\Controllers\GoogleLoginController::class, 'logout'])->name("logout");
Route::get('/callback', [\App\Http\Controllers\GoogleLoginController::class, 'callback'])->name("callback");

Route::name("stock.")->prefix("/stock/")->group(function(){
    Route::get("getNews",[\App\Http\Controllers\StockController::class,'getNews'])->name("getNews");
});