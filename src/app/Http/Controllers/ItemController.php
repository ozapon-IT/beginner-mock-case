<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    public function showItemPage(Item $item)
    {
        $comments = $item->comments()->with('user')->get();

        return view('item', compact('item', 'comments'));
    }
}
