<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function showMypageField()
    {
        $user = Auth::user(); // ユーザー情報を取得

        return view('mypage', compact('user'));
    }
}
