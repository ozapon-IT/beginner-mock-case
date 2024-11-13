<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ItemController extends Controller
{
    public function showItemPage(Item $item)
    {
        $comments = $item->comments()->with('user')->get();

        $profile = Profile::where('user_id', Auth::id())->first();

        return view('item', compact('item', 'comments', 'profile'));
    }
}
