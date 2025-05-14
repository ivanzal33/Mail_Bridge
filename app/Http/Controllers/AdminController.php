<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    function index()
    {
        $users = User::all();
        return view('pages.work_area.admin', compact('users'));
    }

    public function getUserEmails($userId)
    {
        Log::info("Мы туууут");
        $user = User::find($userId);

        if ($user) {
            $emails = $user->emails()->pluck('email');
            return response()->json([
                'emails' => $emails
            ]);
        }
        return response()->json(['error' => 'Пользователь не найден'], 404);
    }
}
