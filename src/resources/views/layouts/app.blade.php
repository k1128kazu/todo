<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    Todo
                </a>
                @auth
                <form method="POST" action="/logout" style="display:inline-block; margin-left:20px;">
                    @csrf
                    <button type="submit" class="logout-button" style="
                    padding:8px 15px;
                    background:#ff5555;
                    color:#fff;
                    border:none;
                    border-radius:4px;
                    cursor:pointer;
                ">
                        ログアウト
                    </button>
                </form>
                @endauth
                <nav>
                    <ul class="header-nav">
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/categories">カテゴリ一覧</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>