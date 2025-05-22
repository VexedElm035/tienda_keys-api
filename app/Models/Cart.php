<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
  protected $fillable = [user_id, key_id];

  public function user(): BelongsTo
  {
      return $this->belongsTo(User::class, 'user_id');
  }

  public function game_key(): BelongsTo
  {
      return $this->belongsTo(GameKey::class, 'key_id');
  }
}
