<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class IndexController extends Controller
{
    public function index()
    {
        // itemsテーブルから商品情報を取得
        $items = Item::select('id','image_path', 'name', 'status')->get();

        // ビューにデータを渡す
        return view('index', compact('items'));
    }
}
