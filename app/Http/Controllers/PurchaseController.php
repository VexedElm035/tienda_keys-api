<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

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
        
        // Asegurar que el user_id coincide con el usuario autenticado
        if ($validated['user_id'] != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $purchase = Purchase::create($validated);
        return response()->json($purchase, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
