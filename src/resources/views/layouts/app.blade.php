<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/base.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="{{ route('top') }}"><img src="img/logo.svg" alt="coachtechロゴ画像"></a>

            <input type="text" placeholder="なにをお探しですか？" class="header__search-box">

            <nav class="header__nav">
                @guest
                <a href="{{ route('login') }}" class="header__link">ログイン</a>
                @endguest

                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="header__link" type="submit">ログアウト</button>
                </form>
                @endauth

                @guest
                <a class="header__link" href="{{ route('login') }}">マイページ</a>
                @endguest

                @auth
                <a class="header__link" href="#mypage">マイページ</a>
                @endauth

                @guest
                <a class="header__link" href="{{ route('login') }}">出品</a>
                @endguest

                @auth
                <a class="header__link" href="#sell">出品</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>