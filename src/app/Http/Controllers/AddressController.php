<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function showAddressChangePage($item)
    {
        return view('address', compact('item'));
    }

    public function changeAddress(AddressRequest $request, $item)
    {
        $address = $request->only('postal_code', 'address', 'building');

        session(['address' => $address]);

        return redirect()->route('purchase', ['item' => $item]);
    }
}
