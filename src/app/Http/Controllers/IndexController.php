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
        $currentTab = $request->query('tab', 'recommend');
        $search = $request->query('search');

        if ($currentTab === 'mylist') {
            // ログインユーザーが「いいね」した商品を取得
            if (auth()->check()) {
                $itemsQuery = Item::whereIn('id', function ($query) {
                    $query->select('item_id')
                        ->from('likes')
                        ->where('user_id', auth()->id());
                })->select('id', 'image_path', 'name', 'status');

                if (!empty($search)) {
                    $itemsQuery->where('name', 'like', '%' . $search . '%');
                }

                $items = $itemsQuery->get();
            } else {
                $items = collect();
            }
        } else {
            // 「おすすめ」商品の取得（ログインユーザーの出品商品を除外）
            $itemsQuery = Item::select('id', 'image_path', 'name', 'status');

            if (auth()->check()) {
                $itemsQuery->where('user_id', '!=', auth()->id());
            }

            // 部分一致検索
            if (!empty($search)) {
                $itemsQuery->where('name', 'like', '%' . $search . '%');
            }

            $items = $itemsQuery->get();
        }

        return view('index', compact('items', 'currentTab', 'search'));
    }
}
