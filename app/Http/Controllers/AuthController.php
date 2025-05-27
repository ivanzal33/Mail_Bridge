<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;
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

        $user = User::where('username', $validated['username'])->first();

        // Проверка пароля вручную!
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'detail' => 'Неверный логин или пароль'
            ], 401);
        }

        // Генерируем и отправляем код на email
        $user->generateTwoFactorCode();

        // Сохраняем статус в сессии, что ждем 2fa
        session(['2fa:user_id' => $user->id]);

        // Отправляем ответ, что требуется 2fa
        return response()->json([
            'message' => 'Требуется подтверждение через email-код',
            'two_factor' => true
        ], 200);
    }



    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|digits:6',
        ]);

        $userId = session('2fa:user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['detail' => 'Пользователь не найден'], 401);
        }

        if (
            $user->two_factor_code !== $request->two_factor_code ||
            now()->gt($user->two_factor_expires_at)
        ) {
            return response()->json(['detail' => 'Код недействителен или истёк'], 401);
        }

        // Только здесь логиним пользователя!
        auth()->login($user);
        $user->resetTwoFactorCode();
        session()->forget('2fa:user_id');

        $redirect = $user->isAdmin()
            ? route('admin.dashboard')
            : route('communications');

        return response()->json([
            'message'  => 'Двухфакторная аутентификация успешна',
            'redirect' => $redirect
        ], 200);
    }




    public function LogoutUser()
    {
        auth()->logout(); // выход из удаленной сесии
        return redirect()->route('page.login'); // перенаправление не авторизованного пользователя домой
    }

}
