<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function posts()
{
    return $this->hasMany(Post::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}

// Friendship relationships
public function sentFriendRequests()
{
    return $this->hasMany(Friendship::class, 'user_id');
}

public function receivedFriendRequests()
{
    return $this->hasMany(Friendship::class, 'friend_id');
}

// Get all friends (accepted friendships)
public function friends()
{
    $sentFriends = $this->sentFriendRequests()->where('status', 'accepted')->with('friend')->get()->pluck('friend');
    $receivedFriends = $this->receivedFriendRequests()->where('status', 'accepted')->with('user')->get()->pluck('user');
    
    return $sentFriends->merge($receivedFriends);
}

// Get friends as a query builder for more flexible queries
public function friendsQuery()
{
    // Get IDs of users who are friends with this user
    $sentFriendIds = $this->sentFriendRequests()->where('status', 'accepted')->pluck('friend_id');
    $receivedFriendIds = $this->receivedFriendRequests()->where('status', 'accepted')->pluck('user_id');
    
    $allFriendIds = $sentFriendIds->merge($receivedFriendIds);
    
    return User::whereIn('id', $allFriendIds);
}

// Check if this user is friends with another user
public function isFriendWith($userId)
{
    return Friendship::areFriends($this->id, $userId);
}

// Get friendship status with another user
public function friendshipStatusWith($userId)
{
    return Friendship::getFriendshipStatus($this->id, $userId);
}

// Simple role helper; returns true if user role is 'admin'
public function isAdmin(): bool
{
    return ($this->role ?? null) === 'admin';
}

// Follow system
public function followers()
{
    return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
}

public function following()
{
    return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
}

public function isFollowing($userId): bool
{
    return $this->following()->where('following_id', $userId)->exists();
}

public function follow($userId): bool
{
    if ($userId == $this->id || $this->isFollowing($userId)) return false;
    $this->following()->attach($userId);
    return true;
}

public function unfollow($userId): bool
{
    if (!$this->isFollowing($userId)) return false;
    $this->following()->detach($userId);
    return true;
}

// Story system
public function stories()
{
    return $this->hasMany(Story::class);
}

public function activeStories()
{
    return $this->stories()->active()->latest();
}

}
