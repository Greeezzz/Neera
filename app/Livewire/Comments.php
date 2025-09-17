<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CommentVote;
use Illuminate\Support\Facades\Auth;

class Comments extends Component
{
    use WithFileUploads;
    public $post;
    public $content, $media;
    public $replyTo = null;
    public $replyContent = '';
    public $replyMedia = null;
    // Edit state
    public $editingCommentId = null;
    public $editContent = '';

    protected $rules = [
        'content' => 'required|string',
        'media'   => 'nullable|image|max:10240',
        'replyContent' => 'required|string',
        'replyMedia' => 'nullable|image|max:10240',
        'editContent' => 'required|string',
    ];

    protected $listeners = [];

    public function mount($post)
    {
        $this->post = $post;
    }

    public function save()
    {
        $this->validate([
            'content' => 'required|string',
            'media' => 'nullable|file|max:10240', // 10MB max
        ]);

        try {
            $path = null;
            if ($this->media) {
                $path = $this->media->store('comments', 'public');
            }

            $comment = $this->post->comments()->create([
                'user_id' => Auth::id(),
                'content' => $this->content,
                'media'   => $path,
            ]);

            $this->reset(['content', 'media']);
            
            session()->flash('message', '💬 Comment posted successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error posting comment: ' . $e->getMessage());
        }
    }

    public function reply($commentId)
    {
        $this->validate([
            'replyContent' => 'required|string',
            'replyMedia' => 'nullable|image|max:10240'
        ]);

        $path = null;
        if ($this->replyMedia) {
            $path = $this->replyMedia->store('comments', 'public');
        }

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->replyContent,
            'parent_id' => $commentId,
            'media' => $path,
        ]);

        $this->reset(['replyContent', 'replyTo', 'replyMedia']);
        
        $this->dispatch('postUpdated');
    }

    public function likeComment($commentId)
    {
        $user = Auth::user();
        $existingVote = CommentVote::where('user_id', $user->id)->where('comment_id', $commentId)->first();
        
        if ($existingVote && $existingVote->vote == 1) {
            $existingVote->delete();
        } else {
            CommentVote::updateOrCreate([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ], [
                'vote' => 1,
            ]);
        }
        
        $this->dispatch('postUpdated');
    }

    public function dislikeComment($commentId)
    {
        $user = Auth::user();
        $existingVote = CommentVote::where('user_id', $user->id)->where('comment_id', $commentId)->first();
        
        if ($existingVote && $existingVote->vote == -1) {
            $existingVote->delete();
        } else {
            CommentVote::updateOrCreate([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ], [
                'vote' => -1,
            ]);
        }
        
        $this->dispatch('postUpdated');
    }

    public function showReply($commentId)
    {
        $this->replyTo = $this->replyTo === $commentId ? null : $commentId;
    }

    public function startEdit($commentId)
    {
        $comment = $this->post->comments()->where('id', $commentId)->first();
        if (!$comment) return;
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) return; // simple auth gate

        $this->editingCommentId = $commentId;
        $this->editContent = $comment->content;
    }

    public function cancelEdit()
    {
        $this->editingCommentId = null;
        $this->editContent = '';
    }

    public function updateComment()
    {
        $this->validateOnly('editContent');
        $comment = $this->post->comments()->where('id', $this->editingCommentId)->first();
        if (!$comment) return;
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) return;

        $comment->update(['content' => $this->editContent]);
        $this->cancelEdit();
        session()->flash('message', '✏️ Comment updated');
        $this->dispatch('postUpdated');
    }

    public function deleteComment($commentId)
    {
        $comment = $this->post->comments()->where('id', $commentId)->first();
        if (!$comment) return;
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) return;

        // Optionally delete votes and nested replies
        foreach ($comment->replies as $reply) {
            $reply->votes()->delete();
            $reply->delete();
        }
        $comment->votes()->delete();
        $comment->delete();

        session()->flash('message', '🗑️ Comment deleted');
        $this->dispatch('postUpdated');
    }

    public function render()
    {
        return view('livewire.comments', [
            'comments' => $this->post->comments()->whereNull('parent_id')->with('user', 'votes', 'replies')->get(),
        ]);
    }
}

