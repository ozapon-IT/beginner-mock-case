<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\FailedLoginResponse;
use Laravel\Fortify\Fortify;
use App\Http\Requests\CustomLoginRequest;

class CustomValidateLogin
{
    public function __invoke(Request $request)
    {
        $loginRequest = app(CustomLoginRequest::class);
        $rules = $loginRequest->rules();
        $messages = $loginRequest->messages();

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}