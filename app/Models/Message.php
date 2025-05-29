<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'purchase_id',
        'subject',
        'content',
        'is_read',
        'type'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime:d M Y H:i' // Formato amigable
    ];

    // Relaciones
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->with('gameKey.game');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Métodos
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Eventos (opcional)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            if ($message->sender_id === null) {
                $message->sender_id = 1; // Sistema como remitente por defecto
            }
        });
    }
}
