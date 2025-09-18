<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Friendship;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Get pending friend requests
        $pendingRequests = Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();
            
        return view('profile.edit', compact('user', 'pendingRequests'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    /**
     * Show user profile page
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->with(['user', 'votes'])->latest()->get();
        $isOwn = auth()->id() === $user->id;
        $followerCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $postCount = $posts->count();
        $friendCount = $user->friends()->count();
        $isFollowing = !$isOwn ? auth()->user()->isFollowing($user->id) : false;

        return view('profile.show', compact(
            'user', 'posts', 'isOwn', 'followerCount', 'followingCount', 'postCount', 'friendCount', 'isFollowing'
        ));
    }
    
    /**
     * Update profile with bio and profile picture
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        $user = Auth::user();
        
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }
        
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->save();
        
        return back()->with('status', 'profile-updated');
    }
}
