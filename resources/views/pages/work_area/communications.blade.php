@extends('layouts.user')
@section('title', 'Связи')
@section('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/style_communications.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="middle.png">
@endsection
@section('scripts')
    <script>
        window.data = @json($data);
        window.emails = @json($emails);
        window.messengers = @json($messengers);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/leader-line"></script>
    <script src="{{ asset('js/communications_index.js') }}"></script>
@endsection
@section('content')

    <div class="wrapper">
        <!-- Боковые блоки -->
        <div class="box email-box">
            <h2>Электронные почты</h2>
            <a href="{{ route('connections') }}" id="emailLink" class="email-link">Добавить электронную почту</a>
            <div class="item-list" id="emailList"></div>
        </div>
        <div class="box center-box">
            <h2>Инструменты</h2>
            <div class="button-container">
                <button class="btn arrow-right">
                    &#8594;
                    <span class="tooltip">Односторонняя связь.<br>Выберите почту для отправки и мессенджер для получения сообщений.<br>Сообщения будут передаваться из почты в мессенджер.</span>
                </button>
                <button class="btn arrow-both">
                    &#8596;
                    <span class="tooltip">Двусторонняя связь.<br>Выберите почту для отправки и мессенджер для получения и отправки сообщений.<br>Сообщения будут передаваться между почтой и мессенджером в обе стороны. </span>
                </button>
                <button class="btn close">
                    &#10006;
                    <span class="tooltip">Удалить.<br>Выберите связь,<br>которую хотите удалить.</span>
                </button>
            </div>
        </div>
        <div class="box messenger-box">
            <h2>Мессенджеры</h2>
            <a href="{{ route('connections') }}" id="messengerLink" class="messenger-link">Добавить мессенджер</a>
            <div class="item-list" id="messengerList"></div>
        </div>
    </div>

@endsection
