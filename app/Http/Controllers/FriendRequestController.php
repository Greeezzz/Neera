<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get pending requests received
        $pendingRequests = Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();
            
        // Get sent requests
        $sentRequests = Friendship::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('friend')
            ->latest()
            ->get();
            
        // Get friends list
        $friends = $user->friends();
        
        return view('friends.index', compact('pendingRequests', 'sentRequests', 'friends'));
    }
}
