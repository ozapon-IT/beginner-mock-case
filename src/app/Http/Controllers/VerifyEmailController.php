<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Http\Controllers\VerifyEmailController as BaseVerifyEmailController;
use Illuminate\Auth\Events\Verified;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerifyEmailController extends BaseVerifyEmailController
{
    /**
     * オーバーライド: メールアドレスの認証を行う
     *
     * @param  \Laravel\Fortify\Http\Requests\VerifyEmailRequest  $request
     * @return \Laravel\Fortify\Contracts\VerifyEmailResponse
     */
    public function __invoke(VerifyEmailRequest $request)
    {
        $userId = $request->route('id');
        $user = User::find($userId);

        if (!$user) {
            Log::error('User not found for email verification', ['user_id' => $userId]);
            abort(404, 'User not found');
        }

        if ($user->hasVerifiedEmail()) {
            Log::info('Email already verified', ['user_id' => $userId]);
            return app(VerifyEmailResponse::class);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            Log::info('Email marked as verified', ['user_id' => $userId]);
        } else {
            Log::error('Failed to mark email as verified', ['user_id' => $userId]);
        }

        return app(VerifyEmailResponse::class);
    }
}
