<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\PostVote;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PostDetail extends Component
{
    use WithFileUploads;
    
    public $post;
    public $content = '';
    public $media = null;
    
    protected $rules = [
        'content' => 'required|string|min:1',
        'media' => 'nullable|image|max:10240',
    ];
    
    public function mount($postId)
    {
        $this->post = Post::with(['user', 'votes', 'comments.user', 'comments.votes'])
            ->withCount([
                'votes as upvotes_count' => function($query) {
                    $query->where('vote', 1);
                },
                'votes as downvotes_count' => function($query) {
                    $query->where('vote', -1);
                },
                'comments'
            ])
            ->findOrFail($postId);
    }

    public function upvote()
    {
        $this->vote('up');
    }

    public function downvote()
    {
        $this->vote('down');
    }

    public function vote($type)
    {
        $userId = Auth::id();
        $voteValue = $type === 'up' ? 1 : -1;
        
        $existingVote = PostVote::where('post_id', $this->post->id)
                               ->where('user_id', $userId)
                               ->first();

        if ($existingVote) {
            if ($existingVote->vote === $voteValue) {
                $existingVote->delete();
            } else {
                $existingVote->update(['vote' => $voteValue]);
            }
        } else {
            PostVote::create([
                'post_id' => $this->post->id,
                'user_id' => $userId,
                'vote' => $voteValue
            ]);
        }

        // Refresh post data
        $this->mount($this->post->id);
        
        session()->flash('message', $type === 'up' ? '👍 Upvoted!' : '👎 Downvoted!');
    }

    public function addComment()
    {
        $validatedData = $this->validate([
            'content' => 'required|string|max:1000',
            'media' => 'nullable|image|max:10240',
        ]);

        $mediaPath = null;
        if ($this->media) {
            $mediaPath = $this->media->store('comments', 'public');
        }

        $comment = new Comment();
        $comment->content = $this->content;
        $comment->user_id = auth()->id();
        $comment->post_id = $this->post->id;
        $comment->media = $mediaPath;
        $comment->save();

        $this->reset(['content', 'media']);
        $this->dispatch('comment-added');
    }

    public function hasUpvoted()
    {
        if (!auth()->check()) return false;
        
        return PostVote::where('post_id', $this->post->id)
                      ->where('user_id', auth()->id())
                      ->where('vote', 1)
                      ->exists();
    }

    public function hasDownvoted()
    {
        if (!auth()->check()) return false;
        
        return PostVote::where('post_id', $this->post->id)
                      ->where('user_id', auth()->id())
                      ->where('vote', -1)
                      ->exists();
    }

    public function render()
    {
        return view('livewire.post-detail');
    }
}
