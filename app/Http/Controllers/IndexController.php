<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function login()
    {
        // Если пользователь уже авторизован, перенаправляем на страницу связей
        if (auth()->check()) {
            return redirect()->route('communications');
        }

        return view('pages.auth.login');
    }

    public function register()
    {
        // Если пользователь уже авторизован, перенаправляем на страницу связей
        if (auth()->check()) {
            return redirect()->route('communications');
        }

        return view('pages.auth.register');
    }

    public function support()
    {
        return view('pages.auth.support');
    }

    public function recovery()
    {
        return view('pages.auth.recovery');

    }
}
