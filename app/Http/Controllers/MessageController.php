<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Get message notifications
    public function getMessageNotifications()
    {
        $authId = Auth::id();

        $users = User::withWhereHas('receivedMessages', function ($q) use ($authId) {
        $q->where('receiver_id', $authId)
          ->latest()
          ->take(1)->with(['sender']);
        })
        ->get();
            return response()->json($users);
    }

    // Send a message
     public function sendMessage(Request $request)
    {

        $sender_id = auth::id();
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' =>  $sender_id,
            'receiver_id' => $request->receiver_id,
            'message_content' => $request->message_content
        ]);

         return response()->json([
        'success' => true,
        'message' => $message,
    ]);
    
    }

    // Get conversation between two users
    public function conversation($userId)
{
    $currentUserId = Auth::id();

    $messages = Message::where(function($q) use ($currentUserId, $userId) {
            $q->where('sender_id', $currentUserId)
              ->where('receiver_id', $userId);
        })
        ->orWhere(function($q) use ($currentUserId, $userId) {
            $q->where('sender_id', $userId)
              ->where('receiver_id', $currentUserId);
        })
        ->orderBy('created_at')
        ->get();

    return response()->json([
        'messages' => $messages
    ]);
}
}
