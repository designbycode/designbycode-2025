<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostsIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public ?string $search = '';

    /**
     * @return View
     */
    #[Title('Tutorials')]
    public function render(): View
    {
        return view('livewire.pages.posts.posts-index', [
            'posts' => Post::search($this->search)->with(['author'])->withTotalVisitCount()->latest()->paginate(10),
        ]);
    }

    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
        ];
    }
}
