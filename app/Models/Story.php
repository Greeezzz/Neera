<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'media_path',
        'media_type',
        'expires_at',
        'is_active',
        'viewers'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'viewers' => 'array',
        'is_active' => 'boolean'
    ];

    protected static function booted()
    {
        // Automatically set expiry to 24 hours from now
        static::creating(function ($story) {
            if (!$story->expires_at) {
                $story->expires_at = Carbon::now()->addHours(24);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', Carbon::now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now());
    }

    public function isExpired()
    {
        return $this->expires_at <= Carbon::now();
    }

    public function hasBeenViewedBy($userId)
    {
        return in_array($userId, $this->viewers ?? []);
    }

    public function markAsViewedBy($userId)
    {
        $viewers = $this->viewers ?? [];
        if (!in_array($userId, $viewers)) {
            $viewers[] = $userId;
            $this->update(['viewers' => $viewers]);
        }
    }

    public function getViewersCount()
    {
        return count($this->viewers ?? []);
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->isExpired()) {
            return 'Expired';
        }
        
        return $this->expires_at->diffForHumans();
    }
}
