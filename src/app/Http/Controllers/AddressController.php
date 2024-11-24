<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function showAddressChangePage(Request $request, $item)
    {
        $payment_method = $request->query('payment_method', null);

        return view('address', compact('item', 'payment_method'));
    }

    public function changeAddress(AddressRequest $request, $item)
    {
        $address = $request->only('postal_code', 'address', 'building');

        session(['address' => $address]);

        if ($request->filled('payment_method')) {
            session(['payment_method' => $request->input('payment_method')]);
        }

        return redirect()->route('purchase', ['item' => $item]);
    }
}
