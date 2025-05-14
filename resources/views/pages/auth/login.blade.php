@extends('layouts.layout')
@section('title', 'Авторизация')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/style_login_register.css') }}">
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
@endsection

