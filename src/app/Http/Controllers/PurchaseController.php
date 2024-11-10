<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function showPurchasePage(Request $request, Item $item)
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $address = $request->session()->get('address', null);

        return view('purchase', compact('item', 'profile', 'address'));
    }
}
