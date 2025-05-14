@extends('layouts.layout')
@section('title', 'Регистрация')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/style_login_register.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/registration_index.js') }}"></script>
@endsection

@section('content')
    <div class="wrapper">
        <form id="registration-form">
            @csrf
            <h1>Регистрация</h1>

            <div class="input-box">
                <input type="text" name="username" id="username" placeholder="Имя пользователя" required>
                <i class='bx bx-user'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" id="email" placeholder="Почта" required>
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Пароль" required>
                <i class='bx bx-lock-alt'></i>
            </div>
            <i class='bx bx-show toggle-password' id="togglePassword"></i>

            <div class="input-box">
                <input type="password" name="repeat_password" id="confirm-password" placeholder="Повторите пароль" required>
                <i class='bx bx-lock-alt'></i>
            </div>
            <i class='bx bx-show toggle-confirm-password' id="toggleConfirmPassword"></i>

            <div class="remember">
                <label>
                    <input type="checkbox" name="rules" id="consent" required> Я согласен на обработку персональных данных</label>
            </div>

            <button type="submit" id="submitSave" class="btn">Зарегистрироваться</button>

            <div class="register-link">
                <p>Уже зарегистрированы? <a href="{{ route('page.login') }}">Войти</a></p>
            </div>

            <div class="tech-support">
                <a href="{{ route('page.support') }}">Техническая поддержка</a>
            </div>
        </form>
    </div>
@endsection


