<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostDetail extends Component
{
    public $post;
    
    public function mount($postId)
    {
        $this->post = Post::with(['user', 'votes', 'comments.user', 'comments.votes'])
            ->findOrFail($postId);
    }

    public function render()
    {
        return view('livewire.post-detail');
    }
}
