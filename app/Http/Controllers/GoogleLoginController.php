<?php

namespace App\Http\Controllers;

use App\Models\StockTradesModel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function redirect()
    {
        $parameters = ['access_type' => 'offline', "prompt" => "consent select_account"];
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/forms'])
            ->with($parameters)// refresh token
            ->redirect();
    }
    
    public function callback(Request $request)
    {
        $userInfo = Socialite::driver('google')->user();
        $model = new User();
        //있으면 넣고 없으면 업데이트
        $user = $model->where([
            ["email", "=", $userInfo->getEmail()],
        ])->first();
        
        $params = [
            'name' => $userInfo->getName(),
            'nick_name' => $userInfo->getNickname(),
            'avatar' => $userInfo->getAvatar(),
            'password' => "NONE",
            'token' => $userInfo->token,
            'refresh_token' => $userInfo->refreshToken,
        ];
        if (!isset($user->id)) {
            $params['email'] = $userInfo->getEmail();
            $params['email_verified_at'] = date("Y-m-d H:i:s");
            $sessionID = $model->insertGetID($params);
        } else {
            $sessionID = $user->id;
            $model->where("id", $sessionID)->update($params);
        }
    
        $stockTradeModel=new StockTradesModel();
        $stockTradeModel->defaultCharge($sessionID);
        Auth::login($user);
        return redirect(route("home"));
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect(route("home"));
    }
    
}