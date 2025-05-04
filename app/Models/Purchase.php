<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = ['user_id', 'total', 'pay_method', 'tax', 'state', 'key_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameKey(): BelongsTo
    {
        return $this->belongsTo(GameKey::class, 'key_id');
    }
}

