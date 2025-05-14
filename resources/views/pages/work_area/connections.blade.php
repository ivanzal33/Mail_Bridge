@extends('layouts.user')
@section('title', 'Подключить новый аккаунт')
@section('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style_connection.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('scripts')
    <script>
        const arrEmails = @json($arrEmails);
        const arrMessengers = @json($arrMessengers);
        const arrTypeMessenger = @json($arrTypeMessenger);
    </script>
    <script src="{{ asset('js/connections_index.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <h1>MailBridge</h1>
        <div class="tabs">
            <div class="tab" onclick="toggleSideMenu('emails')">Электронные почты</div>
            <div class="tab" onclick="toggleSideMenu('messengers')">Мессенджеры</div>
            <a class="tab back-button" href="/communications" style="text-decoration: none;">Назад</a>
        </div>
    </div>
    <div id="side-menu" class="side-menu">
        <span class="close-menu" onclick="closeSideMenu()">×</span>
        <div id="side-menu-content">
            <h2 id="side-menu-title"></h2>
            <div id="form-container" class="form-container">
                <select id="messenger-select" style="display: none;">
                    <option value="">Выберите мессенджер</option>
                </select>
                <input type="text" id="input-address" placeholder="Email/Contact" required>
                <input type="password" id="input-password" placeholder="Token" required style="display: none;">
                <button onclick="saveContact()">Сохранить</button>
            </div>
            <div id="scroll-container" class="scroll-container">
                <ul id="account-list" class="account-list"></ul>
            </div>
        </div>
    </div>
@endsection

