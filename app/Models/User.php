<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens;
    protected $fillable = ['username', 'email', 'password', 'avatar', 'role'];

    public function gameKeys(): HasMany
    {
        return $this->hasMany(GameKey::class, 'seller_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(Support::class, 'user_id_buyer');
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    // Opcional: alias para compatibilidad
    public function cart()
    {
        return $this->cartItems();
    }

    public function messagesReceived(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function messagesSent(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function unreadMessagesCount(): int
    {
        return $this->messagesReceived()->unread()->count();
    }
}
