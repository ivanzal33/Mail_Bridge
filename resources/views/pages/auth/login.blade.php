@extends('layouts.layout')
@section('title', 'Авторизация')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/style_login_register.css') }}">
    <link rel="stylesheet" href="{{ asset('css/two_factor_modal.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/login_index.js') }}"></script>
@endsection

@section('content')
    <div class="wrapper">
        <form id="login-form">
            @csrf
            <h1>Авторизация</h1>

            <!-- Поле для имени пользователя -->
            <div class="input-box">
                <input type="text" id="username" name="username" placeholder="Имя пользователя">
                <i class='bx bx-user'></i>
            </div>

            <!-- Поле для пароля -->
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Пароль">
                <i class='bx bx-lock-alt'></i>
            </div>

            <i class='bx bx-show toggle-password' id="togglePassword"></i>

            <div class="remember">
                <label for=""><input type="checkbox">Запомнить меня</label>
                <a href="{{ route('page.recovery') }}">Забыли пароль?</a>
            </div>

            <button type="submit" id="submitSave" class="btn">Войти</button>

            <div class="register-link">
                <p>Нет аккаунта?<a href="{{ route('page.register') }}"> Регистрация</a></p>
            </div>

            <div class="tech-support">
                <a href="{{ route('page.support') }}">Техническая поддержка</a>
            </div>

            <div class="lock-overlay" id="lockOverlay">
                <div class="lock-message">
                    <p>Форма заблокирована из-за превышения количества попыток</p>
                    <p id="timer" class="timer">Осталось: 30:00</p>
                    <button id="retryBtn" class="retry-btn" style="display: none;">Попробовать снова</button>
                </div>
            </div>
        </form>
    </div>


    <div id="twoFactorModal">
        <div class="twofactor-box">
            <button class="twofactor-close" onclick="hideTwoFactorModal()" title="Закрыть">&times;</button>
            <h3>Введите код из письма</h3>
            <input type="text" id="twoFactorInput" maxlength="6" autocomplete="one-time-code">
            <div id="twoFactorError"></div>
            <button type="button" class="twofactor-btn" onclick="submitTwoFactorCode()">Подтвердить</button>
        </div>
    </div>

@endsection

