<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpatieImage extends Component
{
    public $images;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public array  $attachment,
        public string $alt,
        public string $collection,
    )
    {
        $this->images = Media::query()
            ->whereIn('uuid', $this->attachment)
            ->where('collection_name', $this->collection)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.spatie-image');
    }
}
