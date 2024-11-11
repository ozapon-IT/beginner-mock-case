<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;


class IndexController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend'); // デフォルトは 'recommend'
        $search = $request->input('search'); // 検索キーワードの取得

        if ($tab === 'mylist') {
            // 「マイリスト」タブが選択された場合
            if (Auth::check()) {
                // ログインユーザーが「いいね」した商品を取得
                $items = Item::whereIn('id', function ($query) {
                    $query->select('item_id')
                        ->from('likes')
                        ->where('user_id', Auth::id());
                })->select('id', 'image_path', 'name', 'status')->get();
            } else {
                // 未認証ユーザーの場合は空のコレクション
                $items = collect();
            }
        } else {
            // 「おすすめ」商品の取得（ログインユーザーの出品商品を除外）
            $itemsQuery = Item::select('id', 'image_path', 'name', 'status');

            if (Auth::check()) {
                $itemsQuery->where('user_id', '!=', Auth::id());
            }

            // 部分一致検索
            if (!empty($search)) {
                $itemsQuery->where('name', 'like', '%' . $search . '%');
            }

            $items = $itemsQuery->get();
        }

        return view('index', compact('items', 'tab', 'search'));
    }
}
