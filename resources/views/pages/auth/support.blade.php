@extends('layouts.layout')
@section('title', 'Техническая поддержка')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection
@section('content')
    <div class="wrapper">
        <form>
        <h1>Техническая поддержка</h1>

        <div class="Телефон">
            <p>Телефон: 8 (888) 888-88-88</p>
        </div>

        <div class="Email">
            <p>E-mail: <a href="xxxx@mail.ru">xxxx@mail.ru</a></p>
        </div>

        </form>
    </div>
@endsection


