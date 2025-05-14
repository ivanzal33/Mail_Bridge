@extends('layouts.user')
@section('title', 'Панель администратора')
@section('links')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style_admin.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('scripts')
    <script>window.users = @json($users);</script>
    <script src="{{ asset('js/admin_index.js') }}"></script>
@endsection

@section('content')
    <div class="admin-container">
        <!-- Левая колонка -->
        <div class="managers-box">
            <h2>Менеджеры</h2>
            <div id="managersList" class="managers-list">
                <!-- Сюда будут добавляться менеджеры через JavaScript -->
            </div>
            <button id="addManagerBtn" class="add-manager-btn">Добавить нового менеджера</button>
        </div>

        <!-- Правая колонка -->
        <div class="right-panel">
            <div class="emails-box">
                <div class="email-header-row">
                    <h2 id="emailHeader">Подключенные email для пользователя:</h2>
                    <button id="emailDropdownBtn" class="dropdown-btn">▼</button>
                    <div id="emailDropdown" class="email-dropdown">
                        <!-- Сюда будут добавляться email через JavaScript -->
                    </div>
                </div>

                <div class="charts-container">
                    <div class="chart-block">
                        <canvas id="messagesChart"></canvas>
                    </div>
                    <div class="chart-block">
                        <canvas id="responseTimeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
@endsection

