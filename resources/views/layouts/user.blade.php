<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/user_style.css') }}">
    <link rel="shortcut icon" href="../images/middle.png">
    @yield('links')

    <script src="{{ asset('js/user_index.js') }}"></script>


    <title>@yield('title')</title>
</head>
<body>
<div class="account-menu">
    <button class="account-button" onclick="toggleMenu()">А</button>
    <div class="dropdown-menu" id="dropdownMenu">
        <a href="#">Профиль</a>
        <a href="#">Настройки</a>
        <form method="post" action="{{ route('auth.logoutUser') }}">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    </div>
</div>
<main>
    @yield('content')
</main>
@yield('scripts')
</body>
</html>

