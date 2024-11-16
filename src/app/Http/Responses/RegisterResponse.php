<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        Auth::logout();

        return redirect()->route('login')->with('success', '会員登録が完了しました。登録したメールアドレスにメール認証通知が届くので確認し、メール認証を完了してください。');
    }
}