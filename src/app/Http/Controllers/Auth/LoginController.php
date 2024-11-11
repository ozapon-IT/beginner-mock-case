<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * ログインフォームを表示するメソッド
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理を行うメソッド
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('top', ['tab' => 'mylist']); // ログイン後のリダイレクト先
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }
}
