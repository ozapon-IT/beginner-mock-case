<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $hasProfile = $user->profile && $user->profile->postal_code && $user->profile->address;

        if($hasProfile) {
            return redirect()->route('top', ['tab' => 'mylist']);
        } else {
            return redirect()->route('profile');
        }
    }
}