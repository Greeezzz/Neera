<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function otherParty(User $user)
    {
        return $this->user_one_id === $user->id ? $this->userTwo : $this->userOne;
    }

    public static function between($a, $b)
    {
        [$u1, $u2] = $a < $b ? [$a, $b] : [$b, $a];
        return static::firstOrCreate(
            ['user_one_id' => $u1, 'user_two_id' => $u2],
            ['last_message_at' => now()]
        );
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
        });
    }
}
