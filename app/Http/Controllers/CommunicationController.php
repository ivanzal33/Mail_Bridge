<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Messenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommunicationController extends Controller
{
    public function emails()
    {
        return view('pages.work_area.emails');
    }

    public function communications(Request $request)
    {
        $user = auth()->user();

        $data = $user->emails->map(function ($email) use ($user) {
            $email->messengers = $email->messengersForUser($user->id)->get();
            return $email;
        });

        $emails = $user->emails;
        $messengers = $user->messengers;

        return view('pages.work_area.communications', compact('emails', 'messengers', 'data'));
    }

    public function createCommunication(Request $request)
    {
        $userId = auth()->id();

        $emailId = $request->input('emailId');
        $messengerId = $request->input('messengerId');
        $relationType = $request->input('type') === 'twoWay' ? 'two_way' : 'one_way';

        $record = DB::table('email_messenger')
            ->where('user_id', $userId)
            ->where('email_id', $emailId)
            ->where('messenger_id', $messengerId)
            ->first();

        if ($record) {
            if ($record->relation_type !== $relationType) {
                DB::table('email_messenger')
                    ->where('id', $record->id)
                    ->update([
                        'relation_type' => $relationType,
                        'updated_at' => now(),
                    ]);

                return response()->json(['status' => 'updated', 'message' => 'Тип связи обновлён']);
            }

            return response()->json(['status' => 'exists', 'message' => 'Связь уже существует']);
        }

        DB::table('email_messenger')->insert([
            'user_id' => $userId,
            'email_id' => $emailId,
            'messenger_id' => $messengerId,
            'relation_type' => $relationType,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'created', 'message' => 'Связь создана']);
    }



    public function deleteCommunication(Request $request)
    {
        $userId = auth()->id(); // или другой способ получить user_id

        $emailId = $request->input('emailId');
        $messengerId = $request->input('messengerId');

        $communication = DB::table('email_messenger')
            ->where('user_id', $userId)
            ->where('email_id', $emailId)
            ->where('messenger_id', $messengerId)
            ->first();

        if ($communication) {
            DB::table('email_messenger')
                ->where('id', $communication->id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Связь удалена']);
        }

        return response()->json(['status' => 'not_found', 'message' => 'Связь не найдена'], 404);
    }









}
