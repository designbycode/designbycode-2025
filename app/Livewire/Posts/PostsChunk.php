<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class PostsChunk extends Component
{
    public array $ids = [];

    public function mount(array $ids): void
    {
        $this->ids = $ids;
    }


    public function render(): View
    {
        return view('livewire.posts.posts-chunk', [
            'posts' => Post::query()
                ->orderByRaw('FIELD(id, ' . implode(',', $this->ids) . ')')
                ->whereIn('id', $this->ids)
                ->with('author')
                ->withTotalVisitCount()
                ->get(),
        ]);
    }
}
