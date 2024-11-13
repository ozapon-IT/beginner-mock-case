<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    public function like(Item $item)
    {
        $user = auth()->user();

        if (!$item->likes()->where('user_id', $user->id)->exists()) {
            $item->likes()->create(['user_id' => $user->id]);
        }

        return redirect()->back();
    }

    public function unlike(Item $item)
    {
        $user = auth()->user();

        if ($item->likes()->where('user_id', $user->id)->exists()) {
            $item->likes()->where('user_id', $user->id)->delete();
        }

        return redirect()->back();
    }

}
