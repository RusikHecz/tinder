<?php


namespace App\Http\Controllers;


use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListConversationAndMessages
{
    public function mount()
    {
        return Conversation::query()->with('receiver')->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->first();
    }

    public function render()
    {
        return Conversation::query()->with('receiver')->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->get();
    }

    public function viewMessage(Request $request)
    {
        $conversationId = $request->Conversation_id;

        $findConv =  Conversation::query()->where('id', $conversationId)->value('id');

        return Message::query()->with('user')->where('conversation_id', $findConv)->get();

    }

    public function sendMessage(Request $request)
    {
        $idRoom = $request->conversation_id;
        $message = $request->body;

        Message::create([
           'conversation_id' => $idRoom,
            'user_id' => auth()->id(),
            'body' => $message,
        ]);
    }
}
