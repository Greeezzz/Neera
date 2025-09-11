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

    protected $rules = [
        'content' => 'required|string',
        'media'   => 'nullable|image|max:10240',
        'replyContent' => 'required|string',
    ];

    protected $listeners = [];

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

            $this->post->comments()->create([
                'user_id' => Auth::id(),
                'content' => $this->content,
                'media'   => $path,
            ]);

            $this->reset(['content', 'media']);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error posting comment: ' . $e->getMessage());
        }
    }

    public function reply($commentId)
    {
        $this->validate(['replyContent' => 'required|string']);

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->replyContent,
            'parent_id' => $commentId,
        ]);

        $this->reset(['replyContent', 'replyTo']);
        
        // Emit event untuk update real-time
        $this->dispatch('postUpdated');
    }

    public function likeComment($commentId)
    {
        $user = Auth::user();
        $existingVote = CommentVote::where('user_id', $user->id)->where('comment_id', $commentId)->first();
        
        if ($existingVote && $existingVote->vote == 1) {
            // Jika sudah like, hapus vote
            $existingVote->delete();
        } else {
            // Jika belum vote atau dislike, ubah jadi like
            CommentVote::updateOrCreate([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ], [
                'vote' => 1,
            ]);
        }
        
        // Emit event untuk update real-time
        $this->dispatch('postUpdated');
    }

    public function dislikeComment($commentId)
    {
        $user = Auth::user();
        $existingVote = CommentVote::where('user_id', $user->id)->where('comment_id', $commentId)->first();
        
        if ($existingVote && $existingVote->vote == -1) {
            // Jika sudah dislike, hapus vote
            $existingVote->delete();
        } else {
            // Jika belum vote atau like, ubah jadi dislike
            CommentVote::updateOrCreate([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ], [
                'vote' => -1,
            ]);
        }
        
        // Emit event untuk update real-time
        $this->dispatch('postUpdated');
    }

    public function showReply($commentId)
    {
        $this->replyTo = $this->replyTo === $commentId ? null : $commentId;
    }

    public function render()
    {
        return view('livewire.comments', [
            'comments' => $this->post->comments()->whereNull('parent_id')->with('user', 'votes', 'replies')->get(),
        ]);
    }
}
