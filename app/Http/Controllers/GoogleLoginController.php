<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function redirect() {
        $parameters = ['access_type' => 'offline', "prompt" => "consent select_account"];
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/forms'])
            ->with($parameters)// refresh token
            ->redirect();
    }
    
    public function callback(Request $request) {
        $userInfo = Socialite::driver('google')->user();
        dump($userInfo);
        
    }
    
}