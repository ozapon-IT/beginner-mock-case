<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;

class MypageController extends Controller
{
    public function showMyPage(Request $request)
    {
        $user = Auth::user();

        // 現在のタブを取得（デフォルトは出品した商品）
        $currentTab = $request->input('tab', 'sell');

        $items = [];

        if ($currentTab === 'sell') {
            // 出品した商品（itemsテーブルから取得）
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($currentTab === 'buy') {
            // 購入した商品（ordersテーブルから取得し、itemsテーブルを結合）
            $items = Order::where('user_id', $user->id)
                ->with('item')
                ->get()
                ->pluck('item'); // itemデータのみ抽出
        }

        // プロフィール情報を取得
        $profile = Profile::where('user_id', $user->id)->first();

        return view('mypage', compact('user', 'items', 'currentTab', 'profile'));
    }
}
