<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener solo las compras del usuario autenticado
        $purchases = Purchase::with(['user', 'gameKey.game'])
            ->where('user_id', Auth::id())
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

        if ($validated['user_id'] != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $purchase = Purchase::create($validated);

        // Notificar comprador
        Message::create([
            'sender_id' => 1, // ID del sistema
            'receiver_id' => $purchase->user_id,
            'purchase_id' => $purchase->id,
            'subject' => 'Compra exitosa',
            'content' => 'Has comprado la clave para ' . $purchase->gameKey->game->name,
            'type' => 'purchase'
        ]);

        // Notificar vendedor
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = Purchase::with(['gameKey.game', 'user'])
            ->findOrFail($id);

        // Verificar que el usuario es el dueño de la compra
        if ($purchase->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
