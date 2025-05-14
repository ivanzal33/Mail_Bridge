<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function createUser(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        User::query()->create($validated);
        return response()->json([
            'message' => 'Пользователь успешно создан',
            'redirect' => route('page.login')
        ]);
    }

//    public function LoginUser(LoginRequest $request)
//    {
//        $validated = $request->validated();
//
//        if (auth()->attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
//            $user = auth()->user();
//            $emails = $user->emails;
//            $messengers = $user->messengers;
//
//            session([
//                'emails' => $emails,
//                'messengers' => $messengers
//            ]);
//
//            return response()->json(['message' => 'Успешный вход'], 200);
//        }
//
//        return response()->json(['detail' => 'Неверный логин или пароль'], 401);
//    }
    public function loginUser(LoginRequest $request)
    {
        $validated = $request->validated();

        if (auth()->attempt([
            'username' => $validated['username'],
            'password' => $validated['password']
        ])) {
            $user = auth()->user();

            // Подгружаем связи
            session([
                'emails'     => $user->emails,
                'messengers' => $user->messengers,
            ]);

            // Выбираем куда редиректить
            $redirect = $user->isAdmin()
                ? route('admin.dashboard')
                : route('communications');

            return response()->json([
                'message'  => 'Успешный вход',
                'redirect' => $redirect
            ], 200);
        }

        return response()->json([
            'detail' => 'Неверный логин или пароль'
        ], 401);
    }


    public function LogoutUser()
    {
        auth()->logout(); // выход из удаленной сесии
        return redirect()->route('page.login'); // перенаправление не авторизованного пользователя домой
    }

}
