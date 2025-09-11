<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'friend_id', 'status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
    
    // Helper method untuk check friendship status
    public static function getFriendshipStatus($userId, $friendId)
    {
        // Check both directions
        $friendship = self::where(function($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $userId);
        })->first();
        
        return $friendship ? $friendship->status : null;
    }
    
    // Check if users are friends
    public static function areFriends($userId, $friendId)
    {
        return self::getFriendshipStatus($userId, $friendId) === 'accepted';
    }
}
