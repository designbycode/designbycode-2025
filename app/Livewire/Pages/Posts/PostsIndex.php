<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Articles')]
class PostsIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public ?string $search = '';

    public ?int $page = 1;

    public int $chunkSize = 6;

    public array $chunks = [];

    public function mount(): void
    {
        $this->updateChunks();
    }

    public function updateChunks(): void
    {
        if (! empty($this->search)) {
            // Use Scout search when searching
            $this->chunks = Post::search($this->search)
                ->orderBy('id', 'desc')
                ->get()
                ->pluck('id')
                ->toArray();
        } else {
            // Use a regular query when not searching
            $this->chunks = Post::query()
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();
        }

        $this->page = 1; // Reset page to 1 on new search or mount
    }

    public function updatedSearch(): void
    {
        $this->updateChunks();
    }

    public function loadMore(): void
    {
        if (! $this->hasMorePages()) {
            return;
        }
        $this->page++;
    }

    public function hasMorePages(): bool
    {
        $totalChunks = count($this->getChunkedIds());

        return $this->page < $totalChunks;
    }

    public function getChunkedIds(): array
    {
        return array_chunk($this->chunks, $this->chunkSize);
    }

    public function getCurrentChunks(): array
    {
        $chunkedIds = $this->getChunkedIds();

        return array_slice($chunkedIds, 0, $this->page);
    }

    public function render(): View
    {
        return view('livewire.pages.posts.posts-index');
    }

    /**
     * @return array[]
     */
    protected function queryString(): array
    {
        return [
            'search' => [
                'as' => 'q',
            ],
        ];
    }
}
