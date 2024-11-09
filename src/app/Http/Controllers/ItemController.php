<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    public function showItemPage(Item $item)
    {
        // ビューにデータを渡す
        return view('item', compact('item'));
    }
}
