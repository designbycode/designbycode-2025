<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class PostShow extends Component
{
    public $post;

    public function mount(Post $post): void
    {
        $this->post = $post->load(['author']);

    }


    public function render(): View
    {
        return view('livewire.posts.post-show');
    }
}
