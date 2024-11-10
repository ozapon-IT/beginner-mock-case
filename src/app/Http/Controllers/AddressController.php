<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function showAddressChangePage()
    {
        return view('address');
    }

    public function changeAddress(AddressRequest $request)
    {
        $address = $request->only('postal_code', 'address', 'building');

        session(['address' => $address]);

        return redirect()->route('purchase');
    }
}
