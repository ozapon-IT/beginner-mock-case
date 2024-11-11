<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;

class MypageController extends Controller
{
    public function showMyPage(Request $request)
    {
        $user = Auth::user(); // ログイン中のユーザー情報を取得

        // 現在のタブを取得（デフォルトは出品した商品）
        $currentTab = $request->input('tab', 'sell');

        // 出品した商品と購入した商品を取得
        $items = [];
        if ($currentTab === 'sell') {
            // 出品した商品（itemsテーブルから取得）
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($currentTab === 'buy') {
            // 購入した商品（ordersテーブルから取得し、itemsテーブルを結合）
            $items = Order::where('user_id', $user->id)
                ->with('item') // itemリレーションを使用してitem情報を取得
                ->get()
                ->pluck('item'); // itemデータのみ抽出
        }

        return view('mypage', compact('user', 'items', 'currentTab'));
    }
}
