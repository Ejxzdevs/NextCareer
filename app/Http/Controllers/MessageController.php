<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Send a message
     public function sendMessage(Request $request)
    {

        $userId = auth::id();
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' =>  $userId,
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
