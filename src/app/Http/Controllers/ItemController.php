<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    public function show($id)
    {
        // 指定されたIDの商品情報を取得し、カテゴリと商品の状態を一緒に取得
        $item = Item::with(['categories', 'condition'])->findOrFail($id);

        // ビューにデータを渡す
        return view('item', compact('item'));
    }
}
