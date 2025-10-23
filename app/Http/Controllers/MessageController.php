<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    // Get message notifications
    public function getMessageNotifications()
{
    $authId = Auth::id();

    // Get the latest message_id for each sender
    $latestMessageIds = Message::where('receiver_id', $authId)
        ->selectRaw('MAX(message_id) as message_id')
        ->groupBy('sender_id')
        ->pluck('message_id');

    // Fetch those messages with sender info
    $latestMessages = Message::whereIn('message_id', $latestMessageIds)
        ->with('sender.profile')
        ->orderByDesc('created_at')
        ->get();

    return response()->json($latestMessages);
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

        event(new MessageSent($message));

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

        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUserId)
            ->where('status', 'sent')
            ->update(['status' => 'read']);

    return response()->json([
        'messages' => $messages
    ]);
}
    // Mark all messages as read
    public function markAllAsRead()
    {
        $authId = Auth::id();

        Message::where('receiver_id', $authId)
            ->where('status', 'sent')
            ->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }
}
