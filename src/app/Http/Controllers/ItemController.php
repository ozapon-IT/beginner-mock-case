<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ItemController extends Controller
{
    public function showItemPage(Item $item)
    {
        $comments = $item->comments()->with(['user.profile'])->get();

        return view('item', compact('item', 'comments'));
    }
}
