<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public ?string $search = '';


    public function render(): View
    {
        return view('livewire.pages.posts.index', [
            'posts' => Post::search($this->search)->with('author')->withTotalVisitCount()->latest()->paginate(10)
        ]);
    }
}
