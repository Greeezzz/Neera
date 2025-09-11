<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\PostVote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Posts extends Component
{
    use WithFileUploads;
    public $title, $content, $media;
    public $editingPostId = null;
    public $editTitle, $editContent;

    protected $rules = [
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'media'   => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        'editTitle' => 'required|string|max:255',
        'editContent' => 'required|string',
    ];

    protected $listeners = [];

    public function mount()
    {
        // Tidak perlu mount khusus
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'content' => 'required|min:5',
            'media' => 'nullable|file|max:2048', // 2MB max
        ]);

        try {
            $mediaPath = null;
            
            if ($this->media) {
                $mediaPath = $this->media->store('posts', 'public');
            }

            $post = Post::create([
                'title' => $this->title,
                'content' => $this->content,
                'media' => $mediaPath,
                'user_id' => Auth::id(),
            ]);
            
            // Debug message
            if ($mediaPath) {
                session()->flash('message', 'Post created with media: ' . $mediaPath);
            } else {
                session()->flash('message', 'Post created without media');
            }

            // Reset form
            $this->reset(['title', 'content', 'media']);
            
            // Flash success message  
            session()->flash('message', 'Post created successfully!');
            
            // Force re-render dengan data terbaru
            $this->dispatch('postCreated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating post: ' . $e->getMessage());
        }
    }

    public function upvote($postId)
    {
        $user = Auth::user();
        $existingVote = PostVote::where('user_id', $user->id)->where('post_id', $postId)->first();
        
        if ($existingVote && $existingVote->vote == 1) {
            // Jika sudah upvote, hapus vote
            $existingVote->delete();
        } else {
            // Jika belum vote atau downvote, ubah jadi upvote
            PostVote::updateOrCreate([
                'user_id' => $user->id,
                'post_id' => $postId,
            ], [
                'vote' => 1,
            ]);
        }
        
        // Tidak perlu refresh property, biarkan render() handle
    }

    public function downvote($postId)
    {
        $user = Auth::user();
        $existingVote = PostVote::where('user_id', $user->id)->where('post_id', $postId)->first();
        
        if ($existingVote && $existingVote->vote == -1) {
            // Jika sudah downvote, hapus vote
            $existingVote->delete();
        } else {
            // Jika belum vote atau upvote, ubah jadi downvote
            PostVote::updateOrCreate([
                'user_id' => $user->id,
                'post_id' => $postId,
            ], [
                'vote' => -1,
            ]);
        }
        
        // Tidak perlu refresh property, biarkan render() handle
    }

    public function editPost($postId)
    {
        $post = Post::find($postId);
        
        // Check if user owns this post
        if ($post && $post->user_id === Auth::id()) {
            $this->editingPostId = $postId;
            $this->editTitle = $post->title;
            $this->editContent = $post->content;
        }
    }

    public function updatePost()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editContent' => 'required|string',
        ]);

        $post = Post::find($this->editingPostId);
        
        if ($post && $post->user_id === Auth::id()) {
            $post->update([
                'title' => $this->editTitle,
                'content' => $this->editContent,
            ]);
        }

        $this->cancelEdit();
    }

    public function deletePost($postId)
    {
        $post = Post::find($postId);
        
        // Check if user owns this post
        if ($post && $post->user_id === Auth::id()) {
            $post->delete();
        }
    }

    public function cancelEdit()
    {
        $this->editingPostId = null;
        $this->editTitle = '';
        $this->editContent = '';
    }

    public function refreshPosts()
    {
        // Clear any edit state
        $this->cancelEdit();
        
        // Flash message untuk user feedback
        session()->flash('message', '🔄 Posts refreshed!');
        
        // Force component re-render
        $this->dispatch('postsRefreshed');
    }

    public function render()
    {
        $posts = Post::with(['user', 'votes'])
            ->latest()
            ->limit(10) // Batasi hanya 10 post terbaru
            ->get();
            
        return view('livewire.posts', compact('posts'));
    }
}

