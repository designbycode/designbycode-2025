<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostsIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public ?string $search = '';

    public function render()
    {
        return view('livewire.posts.posts-index', [
            'posts' => Post::search($this->search)->with('author')->withTotalVisitCount()->latest()->paginate(10),
        ]);
    }
}
