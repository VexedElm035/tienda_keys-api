<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\GameKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('gameKey.game')->get();
        return response()->json($cartItems);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key_id' => 'required|exists:game_keys,id'
        ]);

        // Verificar disponibilidad
        $gameKey = GameKey::findOrFail($request->key_id);
        if ($gameKey->state !== 'disponible') {
            return response()->json(['error' => 'Esta clave ya no está disponible'], 422);
        }

        // Evitar duplicados
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('key_id', $request->key_id)
            ->first();

        if ($existingItem) {
            return response()->json(['message' => 'Este producto ya está en tu carrito']);
        }

        $cartItem = Cart::create([
            'user_id' => Auth::id(),
            'key_id' => $request->key_id
        ]);

        return response()->json($cartItem->load('gameKey.game'), 201);
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(null, 204);
    }

    public function count()
    {
        $count = Auth::user()->cartItems()->count();
        return response()->json(['count' => $count]);
    }

    // app/Http/Controllers/CartController.php
    public function clear()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $cartItems = $user->cartItems()->get();
            $deletedCount = 0;

            foreach ($cartItems as $item) {
                $this->destroy($item->id); // Llama al método destroy para cada item
                $deletedCount++;
            }

            return response()->json([
                'message' => 'Carrito vaciado correctamente',
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al vaciar el carrito',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
