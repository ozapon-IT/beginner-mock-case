<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function storeComment(CommentRequest $request, Item $item)
    {
        Comment::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('item', ['item' => $item->id]);
    }
}
