<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Events\NewMessage;

class ChatController extends Controller
{
    // Display the chat view
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get(); // Get all users except the authenticated user
        return view('chat.index', compact('users'));
    }

    // Send a new message
    public function sendMessage(Request $request)
    {
        $sender = auth()->user();
        $receiverId = $request->input('receiver_id');
        $messageText = $request->input('message');

        // Create a new message
        $message = new Message([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => $messageText,
        ]);

        $message->save();

        // Broadcast the message to the receiver's channel using Pusher
        broadcast(new NewMessage($message))->toOthers();

        return response()->json(['message' => 'Message sent']);
    }

    // Fetch messages for a specific conversation
    public function fetchMessages($receiverId)
    {
        // Get messages between the authenticated user and the specified receiver
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json(['messages' => $messages]);
    }

    public function show($receiverId)
    {
        // Retrieve the receiver's information
        $receiver = User::findOrFail($receiverId);

        // Load previous messages for this chat
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        // Fetch all users
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat.show', compact('receiver', 'messages', 'users', 'receiverId'));
    }
}
