<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class PostsShow extends Component
{
    public object $post;
    public ?string $title;

    /**
     * @param Post $post
     * @return void
     */
    public function mount(Post $post): void
    {
        $this->post = $post->load('author');

        defer(function () {
            $this->post->visit();
        });
    }

    /**
     * @return View
     */

    public function render(): View
    {
        return view('livewire.pages.posts.posts-show')->title($this->post->title);
    }
    
}
