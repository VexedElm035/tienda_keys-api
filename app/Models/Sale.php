<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = ['game_id', 'discount_percentage', 'start_date', 'end_date'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gameKeys(): HasMany
    {
        return $this->hasMany(GameKey::class);
    }
}

