<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get stories from followed users (and own stories)
        $followingIds = $user->following()->pluck('following_id')->push($user->id);
        
        // Group stories by user with their active stories
        $storiesData = User::whereIn('id', $followingIds)
            ->whereHas('stories', function($query) {
                $query->active();
            })
            ->with(['activeStories'])
            ->get()
            ->map(function($user) {
                return [
                    'user' => $user,
                    'stories' => $user->activeStories,
                    'has_unseen' => $user->activeStories->some(function($story) {
                        return !$story->hasBeenViewedBy(Auth::id());
                    })
                ];
            });

        return view('stories.index', compact('storiesData'));
    }

    public function create()
    {
        return view('stories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        
        // Handle file upload
        $mediaPath = null;
        $mediaType = null;
        
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaPath = $file->store('stories', 'public');
            $mediaType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        }

        $story = Story::create([
            'user_id' => $user->id,
            'caption' => $request->caption,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        return redirect()->route('stories.show', $story)->with('success', 'Story created successfully!');
    }

    public function show(Story $story)
    {
        // Mark as viewed if not already viewed by current user
        if (!$story->hasBeenViewedBy(Auth::id())) {
            $story->markAsViewedBy(Auth::id());
        }

        return view('stories.show', compact('story'));
    }

    public function userStories(User $user)
    {
        $stories = $user->activeStories;
        
        // If not the owner, redirect to story viewer instead of grid view
        if (Auth::id() !== $user->id) {
            if ($stories->count() > 0) {
                return redirect()->route('user.stories.view', ['user' => $user, 'index' => 0]);
            } else {
                return redirect()->route('stories.index')->with('error', 'No active stories found.');
            }
        }
        
        $currentIndex = 0;
        
        return view('stories.user', compact('stories', 'user', 'currentIndex'));
    }

    public function viewStory(User $user, $index = 0)
    {
        $stories = $user->activeStories;
        
        if (!$stories->count() || $index >= $stories->count()) {
            return redirect()->route('stories.index');
        }

        $story = $stories->get($index);
        
        // Mark as viewed
        if (!$story->hasBeenViewedBy(Auth::id())) {
            $story->markAsViewedBy(Auth::id());
        }

        return view('stories.view', compact('stories', 'user', 'story', 'index'));
    }

    public function destroy(Story $story)
    {
        // Only allow deletion by story owner
        if ($story->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete media file if exists
        if ($story->media_path) {
            Storage::disk('public')->delete($story->media_path);
        }

        $story->delete();

        return redirect()->route('profile.show', Auth::user())->with('success', 'Story deleted successfully!');
    }
}
