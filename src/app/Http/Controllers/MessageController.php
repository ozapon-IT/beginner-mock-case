<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Item;
use App\Models\Message;

class MessageController extends Controller
{
    public function create(Item $item)
    {
        $item->load('order.user.profile');

        $messages = $item->messages()
            ->with('user.profile')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('trading', compact('item', 'messages'));
    }

    public function store(MessageRequest $request, Item $item)
    {
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('messages', 'public');
            $request->image_path = $path;
        }

        Message::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'body' => $request->body,
            'image_path' => $request->image_path,
        ]);

        return redirect()->route('trading.create', ['item' => $item]);
    }

    public function update(MessageRequest $request, Item $item, Message $message)
    {
        $message->update($request->only('body'));

        return redirect()->route('trading.create', ['item' => $item]);
    }

    public function destroy(Item $item, Message $message)
    {
        $message->delete();

        return redirect()->route('trading.create', ['item' => $item]);
    }
}
