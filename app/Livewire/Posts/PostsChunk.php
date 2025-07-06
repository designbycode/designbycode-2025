<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class PostsChunk extends Component
{

    public array $ids = [];

    public function render(): View
    {
        return view('livewire.posts.posts-chunk', [
            'posts' => Post::query()
                ->latest()
                ->orderByRaw('FIELD(id, ' . implode(',', $this->ids) . ')')
//                ->orderBy('id', 'desc')
                ->whereIn('id', $this->ids)
                ->with('author')
                ->withTotalVisitCount()
                ->get(),
        ]);
    }
}
