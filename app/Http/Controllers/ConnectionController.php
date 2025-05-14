<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\MessengerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Email;
use App\Models\Messenger;
use Illuminate\Support\Facades\Log;

class ConnectionController extends Controller
{
    public function connections(Request $request)
    {
        $user = Auth::user();
        $arrEmails = $user->emails;
        $arrMessengers = $user->messengers;
        $arrTypeMessenger = ['whatsapp', 'telegram', 'vk'];
        return view('pages.work_area.connections', compact('arrEmails', 'arrMessengers', 'arrTypeMessenger'));
    }

    public function createEmail(EmailRequest $request)
    {
        $validated = $request->validated();

        $token = hash('sha256', $validated['emailPassword']);

        $email = Email::firstOrCreate([
            'email' => $validated['emailAddress'],
            'token' => $token,
        ]);
        if ($email->wasRecentlyCreated) {
            Auth::user()->emails()->attach($email->id);
        }

        return response()->json([
            'message' => 'Почта успешно добавлена',
            'email' => $email
        ]);
    }
    public function createMessenger(MessengerRequest $request)
    {
        $validated = $request->validated();

        $messenger = Messenger::firstOrCreate([
            'type' => $validated['type'],
            'value' => $validated['value'],
        ]);

        if ($messenger->wasRecentlyCreated) {
            Auth::user()->messengers()->attach($messenger->id);
        }

        return response()->json([
            'message' => 'Мессенджер успешно добавлен',
            'messenger' => $messenger
        ]);
    }
    public function deleteEmail(Request $request)
    {
        $request->validate([
            'emailAddress' => 'required|email',
        ]);

        $user = Auth::user();

        $email = $user->emails()->where('email', $request->emailAddress)->first();

        if (!$email) {
            return response()->json(['message' => 'Почта не найдена'], 404);
        }

        // Отвязываем email от пользователя
        $user->emails()->detach($email->id);

        // Удаляем email, если он больше не привязан ни к одному пользователю
        if ($email->users()->count() === 0) {
            $email->delete();
        }

        return response()->json(['message' => 'Почта успешно удалена']);
    }
    public function deleteMessenger(Request $request)
    {
        $request->validate([
            'type' => 'required|in:whatsapp,telegram,vk',
            'value' => 'required|string',
        ]);

        $user = Auth::user();

        $messenger = $user->messengers()
            ->where('type', $request->type)
            ->where('value', $request->value)
            ->first();

        if (!$messenger) {
            return response()->json(['message' => 'Мессенджер не найден'], 404);
        }

        // Отвязываем мессенджер от пользователя
        $user->messengers()->detach($messenger->id);

        // Удаляем, если не используется больше ни у кого
        if ($messenger->users()->count() === 0) {
            $messenger->delete();
        }

        return response()->json(['message' => 'Мессенджер успешно удалён']);
    }

}
