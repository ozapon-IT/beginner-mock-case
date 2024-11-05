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
            <a class="header__logo" href="/"><img src="img/logo.svg" alt="ロゴ画像"></a>
            <input type="text" placeholder="なにをお探しですか？" class="header__search-box">
            <nav class="header__nav">
                <a href="#login" class="header__link">ログイン</a>
                <a href="#mypage" class="header__link">マイページ</a>
                <button class="header__button">出品</button>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>