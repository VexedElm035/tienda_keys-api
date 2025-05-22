<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
class CartController extends Controller
{
   public function show($id)
    {
        return Cart::findOrFail($id)::with('user', 'game_key');
    }
}
