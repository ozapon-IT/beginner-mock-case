<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面 - COACHTECHフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="{{ route('top') }}"><img src="{{ asset('img/logo.svg') }}" alt="coachtechロゴ画像"></a>
        </div>
    </header>

    <main>
        <div class="login">
            <h1 class="login__title">ログイン</h1>

            <form class="login__form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="login__form-group">
                    <label class="login__label" for="email">メールアドレス</label>

                    <input class="login__input" type="text" id="email" name="email" value="{{ old('email') }}">

                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="login__form-group">
                    <label class="login__label" for="password">パスワード</label>

                    <input class="login__input" type="password" id="password" name="password">

                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    @if(session('error'))
                        <span class="error-message">{{ session('error') }}</span>
                    @endif
                </div>

                <button class="login__button" type="submit">ログインする</button>
            </form>

            <div class="login__register-link">
                <a href="{{ route('register') }}">会員登録はこちら</a>
            </div>
        </div>
    </main>
</body>
</html>