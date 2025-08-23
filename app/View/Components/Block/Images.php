<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Images extends Component
{
    public $images;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $alt,
        public string $collectionName,
        public Model  $model,
    )
    {
        if ($this->collectionName) {
            $this->images = $this->model->getMedia($this->collectionName);
        } else {
            $this->images = collect();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.images');
    }
}
