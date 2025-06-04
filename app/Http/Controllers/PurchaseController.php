<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Message;

class PurchaseController extends Controller
{
    public function index()
    {
        // Quitamos el filtro por usuario (¡Ahora muestra TODAS las compras!)
        $purchases = Purchase::with(['user', 'gameKey.game'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($purchases);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'key_id' => 'required|exists:game_keys,id',
            'pay_method' => 'required|string',
            'total' => 'required|numeric',
            'tax' => 'required|numeric',
            'state' => 'required|string',
        ]);

        // Quitamos la verificación de autorización
        $purchase = Purchase::create($validated);

        // Mensajes de compra/venta (sin cambios, pero cuidado con la exposición de datos)
        Message::create([
            'sender_id' => 1,
            'receiver_id' => $purchase->user_id,
            'purchase_id' => $purchase->id,
            'subject' => 'Compra exitosa',
            'content' => 'Has comprado la clave para ' . $purchase->gameKey->game->name,
            'type' => 'purchase'
        ]);

        Message::create([
            'sender_id' => 1,
            'receiver_id' => $purchase->gameKey->seller_id,
            'purchase_id' => $purchase->id,
            'subject' => 'Venta realizada',
            'content' => $purchase->user->username . ' ha comprado tu clave de ' . $purchase->gameKey->game->name,
            'type' => 'sale'
        ]);

        return response()->json($purchase, 201);
    }

    public function show($id)
    {
        // Quitamos la verificación de autorización
        $purchase = Purchase::with(['gameKey.game', 'user'])
            ->findOrFail($id);

        return response()->json($purchase);
    }

    // ... (métodos edit/update/destroy sin cambios)
}