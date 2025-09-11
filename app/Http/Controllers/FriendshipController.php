<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    public function sendRequest($userId)
    {
        $currentUser = Auth::id();
        
        // Check if friendship already exists
        $existingFriendship = Friendship::where(function($query) use ($currentUser, $userId) {
            $query->where('user_id', $currentUser)->where('friend_id', $userId);
        })->orWhere(function($query) use ($currentUser, $userId) {
            $query->where('user_id', $userId)->where('friend_id', $currentUser);
        })->first();
        
        if ($existingFriendship) {
            return back()->with('error', 'Friend request already exists or you are already friends.');
        }
        
        // Create friend request
        Friendship::create([
            'user_id' => $currentUser,
            'friend_id' => $userId,
            'status' => 'pending'
        ]);
        
        return back()->with('message', 'Friend request sent!');
    }
    
    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        // Only the receiver can accept
        if ($friendship->friend_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $friendship->update(['status' => 'accepted']);
        
        return back()->with('message', 'Friend request accepted!');
    }
    
    public function rejectRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        
        // Only the receiver can reject
        if ($friendship->friend_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $friendship->update(['status' => 'rejected']);
        
        return back()->with('message', 'Friend request rejected.');
    }
    
    public function removeFriend($userId)
    {
        $currentUser = Auth::id();
        
        // Find and delete friendship
        Friendship::where(function($query) use ($currentUser, $userId) {
            $query->where('user_id', $currentUser)->where('friend_id', $userId);
        })->orWhere(function($query) use ($currentUser, $userId) {
            $query->where('user_id', $userId)->where('friend_id', $currentUser);
        })->delete();
        
        return back()->with('message', 'Friend removed.');
    }
}
