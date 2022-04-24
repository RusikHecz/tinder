<?php


namespace App\Http\Controllers;


use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class ListConversationAndMessages
{
    public function mount()
    {
        return Conversation::query()->with('receiver')->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->first();
    }

    public function render(Request $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        return Conversation::query()->with('receiver')->where('sender_id', $user_id)
            ->orWhere('receiver_id', $user_id)
            ->get();
    }

    public function viewMessage(Request $request)
    {

        $conversationId = $request->Conversation_id;

        $findConv =  Conversation::query()->with('receiver')->with('sender')
            ->where('id', $conversationId)->value('id');

        return Message::query()->with('user')->where('conversation_id', $findConv)->get();

    }

    public function sendMessage(Request $request)
    {
        $idRoom = $request->conversation_id;
        $message = $request->body;

        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        Message::create([
           'conversation_id' => $idRoom,
            'user_id' => $user_id,
            'body' => $message,
        ]);

        $response = [
            'success' => 'true',
        ];

        return response($response, 201);
    }

    public function createChat(Request $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        $sender_id = $user_id;
        $receiver_id = $request->receiver_id;

        Conversation::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
        ]);

        $response = [
            'success' => 'true',
        ];

        return response($response, 201);
    }
}
