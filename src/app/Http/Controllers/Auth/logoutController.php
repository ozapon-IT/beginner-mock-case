<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * ログアウト処理を行うメソッド
     */
    public function logout(Request $request)
    {
        // ユーザーをログアウトさせる
        Auth::guard('web')->logout();

        // セッションを無効化
        $request->session()->invalidate();

        // CSRFトークンを再生成
        $request->session()->regenerateToken();

        // ログアウト後のリダイレクト先を指定
        return redirect()->route('top');
    }
}
