<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messages = $user->messagesReceived()
            ->with(['sender', 'purchase.gameKey.game'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'messages' => $messages,
            'unread_count' => $user->unreadMessagesCount()
        ]);
    }

    public function show($id)
    {
        $message = Message::with(['sender', 'purchase.gameKey.game'])
            ->findOrFail($id);

        // Verificar permisos
        if ($message->receiver_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Marcar como leído si no lo está
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return response()->json($message);
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);

        if ($message->receiver_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadMessagesCount()
        ]);
    }
}
