<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // Sin autenticación: ahora obtiene todos los mensajes (¡Cuidado con la privacidad!)
        $messages = Message::with(['sender', 'purchase.gameKey.game'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'messages' => $messages,
            'unread_count' => 0 // Ya no hay un usuario autenticado para contar mensajes no leídos
        ]);
    }

    public function show($id)
    {
        $message = Message::with(['sender', 'purchase.gameKey.game'])
            ->findOrFail($id);

        // Quitamos la verificación de receptor
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return response()->json($message);
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        // Sin autenticación, no hay forma de contar mensajes no leídos de un usuario
        return response()->json(['count' => 0]);
    }
}