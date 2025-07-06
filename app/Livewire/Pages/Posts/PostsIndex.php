<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tutorials')]
class PostsIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public ?string $search = '';

    public ?int $page = 1;

    public array $chunks = [];


    public function mount(): void
    {
<<<<<<< HEAD
        $this->chunks = Post::orderBy('id', 'desc')->pluck('id')->chunk(6)->toArray();
=======
        $this->updateChunks();
    }

    public function updateChunks(): void
    {
        $query = Post::query();

        if (!empty($this->search)) {
            $query->search($this->search);
        }

        $this->chunks = $query->orderBy('id', 'desc')->pluck('id')->chunk(6)->toArray();
        $this->page = 1; // Reset page to 1 on new search or mount
    }

    public function updatedSearch(): void
    {
        $this->updateChunks();
>>>>>>> loading-scroll
    }

    /**
     * @return void
     */
    public function loadMore(): void
    {
        if (!$this->hasMorePages()) {
            return;
        }
        $this->page++;
    }

<<<<<<< HEAD
=======
    /**
     * @return bool
     */
>>>>>>> loading-scroll
    public function hasMorePages(): bool
    {
        return $this->page < count($this->chunks);
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.pages.posts.posts-index');
    }

<<<<<<< HEAD
=======
    /**
     * @return array{search: array{as: string}}
     */
>>>>>>> loading-scroll
    protected function queryString(): array
    {
        return [
            'search' => [
                'as' => 'q',
            ],
        ];
    }
}
