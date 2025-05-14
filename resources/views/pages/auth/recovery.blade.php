@extends('layouts.layout')
@section('title', 'Техническая поддержка')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/style_recovery.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/recovery_index.js') }}"></script>
@endsection


@section('content')
    <div class="wrapper">
        <form id="reset-form">
            @csrf
            <h1>Восстановление пароля</h1>

            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Введите ваш email" required>
                <i class='bx bx-envelope'></i>
            </div>

            <button type="submit" id="submitSave" class="btn">Отправить ссылку</button>

            <div class="register-link">
                <p><a href="{{ route('page.login') }}">Назад к входу</a></p>
            </div>
        </form>
    </div>
@endsection


