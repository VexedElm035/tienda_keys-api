<?php

namespace App\Http\Controllers;

use App\Models\GameKey;
use Illuminate\Http\Request;

class GameKeyController extends Controller
{
    public function index()
    {
        return GameKey::with('seller', 'game')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'state' => 'required|string',
            'region' => 'required|string',
            'price' => 'required|numeric',
            'tax' => 'required|numeric',
            'delivery_time' => 'required|string',
            'seller_id' => 'required|exists:users,id',
            'platform' => 'required|string',
            'sale_id' => 'nullable|exists:sales,id'
        ]);
        $gameKey = GameKey::create($validated);
        return response()->json($gameKey, 200);
    }

    public function show($id)
    {
        return GameKey::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'game_id' => 'sometimes|exists:games,id',
            'state' => 'sometimes|string',
            'region' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'tax' => 'sometimes|numeric',
            'delivery_time' => 'sometimes|string',
            'seller_id' => 'sometimes|exists:users,id',
            'platform' => 'sometimes|string',
            'sale_id' => 'nullable|exists:sales,id'
        ]);

        $gameKey = GameKey::findOrFail($id);
        $gameKey->update($validated);
        return response()->json($gameKey);
    }

    public function destroy($id)
    {
        $gameKey = GameKey::findOrFail($id);
        $gameKey->delete();
        return response()->json(['message' => 'GameKey deleted successfully']);
    }
}
