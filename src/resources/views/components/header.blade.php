@props(['search' => true, 'nav' => true, 'currentTab' => null])

<header class="header">
    <div class="header__container">
        <a class="header__logo" href="{{ route('top') }}">
            <img src="{{ asset('img/logo.svg') }}" alt="coachtechロゴ画像">
        </a>

        @if ($search)
            <div class="header__search-form">
                <form id="search-form" action="{{ route('top') }}" method="GET">
                    <input type="hidden" name="tab" value="{{ request('tab', $currentTab ?? 'recommend') }}">
                    <input class="header__search-box" type="text" placeholder="なにをお探しですか？" name="search" value="{{ request('search') }}">
                </form>
            </div>
        @endif

        @if ($nav)
            <nav class="header__nav">
                @guest
                    <a class="header__link" href="{{ route('login') }}">ログイン</a>

                    <a class="header__link" href="{{ route('login') }}">マイページ</a>

                    <a class="header__link header__link--sell" href="{{ route('login') }}">出品</a>
                @endguest

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="header__button" type="submit">ログアウト</button>
                    </form>

                    <a class="header__link" href="/mypage">マイページ</a>

                    <a class="header__link header__link--sell" href="{{ route('sell') }}">出品</a>
                @endauth
            </nav>
        @endif
    </div>
</header>