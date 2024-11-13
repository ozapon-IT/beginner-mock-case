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
            <a class="header__logo" href="{{ route('top') }}"><img src="{{ asset('img/logo.svg') }}" alt="coachtechロゴ画像"></a>

            <div class="header__search-form">
                <input class="header__search-box" type="text" placeholder="なにをお探しですか？" name="search" form="search-form">

                <form id="search-form" action="{{ route('top') }}" method="GET">
                    <input type="hidden" name="tab" value="recommend">
                </form>
            </div>

            <nav class="header__nav">
                @guest
                    <a class="header__link" href="{{ route('login') }}">ログイン</a>
                @endguest

                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="header__button" type="submit">ログアウト</button>
                </form>
                @endauth

                @guest
                    <a class="header__link" href="{{ route('login') }}">マイページ</a>
                @endguest

                @auth
                    <a class="header__link" href="/mypage">マイページ</a>
                @endauth

                @guest
                    <a class="header__link header__link--sell" href="{{ route('login') }}">出品</a>
                @endguest

                @auth
                    <a class="header__link header__link--sell" href="{{ route('sell') }}">出品</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    @yield('script')
</body>

</html>