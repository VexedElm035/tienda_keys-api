<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Support extends Model
{
    protected $table = 'support';
    protected $fillable = ['user_id_seller', 'user_id_buyer', 'issue', 'description', 'state', 'game_id'];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_buyer');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_seller');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id');
    }
}

