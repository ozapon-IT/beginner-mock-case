<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
// use App\Actions\Fortify\ResetUserPassword;
// use App\Actions\Fortify\UpdateUserPassword;
// use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
// use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\LoginRequest;
// use App\Http\Responses\LoginResponse;
// use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::ignoreRoutes();

        Fortify::createUsersUsing(CreateNewUser::class);
        // Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        // Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        // Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        // カスタム認証処理の定義
        // Fortify::authenticateUsing(function (Request $request) {
        //     // LoginRequestのインスタンスを作成
        //     $loginRequest = LoginRequest::createFrom($request);
        //     $loginRequest->setContainer(app()); // コンテナを設定
        //     $loginRequest->validateResolved(); // バリデーションを実行

        //     // バリデーション済みのデータを取得
        //     $credentials = $loginRequest->validated();

        //     // ユーザーの取得
        //     $user = \App\Models\User::where('email', $credentials['email'])->first();

        //     // 認証の確認
        //     if ($user && Hash::check($credentials['password'], $user->password)) {
        //         return $user;
        //     }

        //     // 認証失敗時のエラー追加
        //     $request->session()->flash('error', 'ログイン情報が登録されていません。');

        //     return null;
        // });
    }
}