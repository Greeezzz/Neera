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
    public $search = '';
    public $editingPostId = null;
    public $editTitle, $editContent;

    protected $rules = [
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'media'   => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        'editTitle' => 'required|string|max:255',
        'editContent' => 'required|string',
    ];

    public $showingComments = [];
    public $showingPostDetail = null;

    protected $listeners = ['deletePost'];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        // Tidak perlu mount khusus
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'content' => 'required|min:5',
            'media' => 'nullable|file|max:2048',
        ]);

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
        
        // Reset form
        $this->reset(['title', 'content', 'media']);
        
        // Flash success message  
        session()->flash('message', 'Post created successfully!');
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

    public function vote($postId, $type)
    {
        $post = Post::find($postId);
        if (!$post) return;

        $userId = Auth::id();
        $voteValue = $type === 'up' ? 1 : -1; // Convert to database format
        
        // Check if user already voted
        $existingVote = PostVote::where('post_id', $postId)
                               ->where('user_id', $userId)
                               ->first();

        if ($existingVote) {
            if ($existingVote->vote === $voteValue) {
                // Remove vote if clicking same type
                $existingVote->delete();
            } else {
                // Change vote type
                $existingVote->update(['vote' => $voteValue]);
            }
        } else {
            // Create new vote
            PostVote::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'vote' => $voteValue
            ]);
        }

        session()->flash('message', $type === 'up' ? '👍 Upvoted!' : '👎 Downvoted!');
    }

    public function toggleComments($postId)
    {
        if (isset($this->showingComments[$postId])) {
            unset($this->showingComments[$postId]);
        } else {
            $this->showingComments[$postId] = true;
        }
    }

    public function showComments($postId)
    {
        // TODO: Implement comments modal/section
        session()->flash('message', '💬 Comments feature coming soon!');
    }

    public function showPostDetail($postId)
    {
        return redirect()->route('post.detail', $postId);
    }

    public function closePostDetail()
    {
        $this->showingPostDetail = null;
    }

    public function refreshPosts()
    {
        // Clear any edit state
        $this->cancelEdit();
        
        // Flash message untuk user feedback
        session()->flash('message', '🔄 Posts refreshed!');
    }

    public function render()
    {
        $posts = Post::with(['user', 'votes'])
            ->withCount([
                'votes as upvotes_count' => function($query) {
                    $query->where('vote', 1); // 1 = upvote
                },
                'votes as downvotes_count' => function($query) {
                    $query->where('vote', -1); // -1 = downvote
                }
            ])
            ->when($this->search, function($q) {
                $s = '%'.trim($this->search).'%';
                $q->where(function($qq) use ($s) {
                    $qq->where('title', 'like', $s)
                       ->orWhere('content', 'like', $s)
                       ->orWhereHas('user', function($uq) use ($s) {
                           $uq->where('name', 'like', $s);
                       });
                });
            })
            ->latest()
            ->limit(10)
            ->get();
            
        return view('livewire.posts', compact('posts'));
    }
}

