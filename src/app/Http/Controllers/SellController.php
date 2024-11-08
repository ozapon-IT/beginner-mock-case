<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;

class SellController extends Controller
{
    public function show()
    {
        // カテゴリーと商品状態のデータを取得
        $categories = Category::all();
        $conditions = Condition::all();

        // ビューにデータを渡す
        return view('sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        // 画像の保存
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('storage', 'public');
        }

        // itemsテーブルに保存
        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition_id' => $request->condition_id,
            'image_path' => $path ?? null,
            'status' => '在庫あり',
        ]);

        // 中間テーブル category_item に保存
        $item->categories()->attach($request->category_id);

        return redirect()->route('sell')->with('success', '商品を出品しました');
    }
}
