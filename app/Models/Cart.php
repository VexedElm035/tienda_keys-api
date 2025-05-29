<?php

// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['user_id', 'key_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function gameKey()
    {
        return $this->belongsTo(GameKey::class, 'key_id')->with('game');
    }
}