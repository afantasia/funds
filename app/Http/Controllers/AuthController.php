<?php

namespace App\Http\Controllers;

use App\Models\StockTradesModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * 로그인 폼 표시
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 이메일+비밀번호 로그인 처리
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    /**
     * 회원가입 폼 표시
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * 회원가입 처리 (이메일, 비밀번호, 이름) + defaultCharge 호출
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $stockTradeModel = new StockTradesModel();
        $stockTradeModel->defaultCharge($user->id);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
