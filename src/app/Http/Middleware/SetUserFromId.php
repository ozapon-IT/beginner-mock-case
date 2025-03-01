<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class SetUserFromId
{
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->route('id');

        $user = User::find($userId);

        if ($user) {
            // リクエストにユーザーを設定
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        }

        return $next($request);
    }
}