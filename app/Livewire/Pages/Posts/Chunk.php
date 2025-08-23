<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class Chunk extends Component
{
    public array $ids = [];

    public function mount(array $ids): void
    {
        $this->ids = $ids;
    }

    public function render(): View
    {
        return view('livewire.pages.posts.chunk', [
            'posts' => Post::query()
                ->live()
                ->orderByRaw('FIELD(id, ' . implode(',', $this->ids) . ')')
                ->whereIn('id', $this->ids)
                ->with('user')
                ->withTotalVisitCount()
                ->get(),
        ]);
    }
}
