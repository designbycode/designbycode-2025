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
        public array  $attachment,
        public string $alt,
        public string $blockId,
        public Model  $model,
    )
    {
        $query = $this->model->media();

        if ($this->blockId) {
            $query->whereRaw("json_extract(custom_properties, '$.block_id') = ?", [$this->blockId]);
        } else {
            $query->whereIn('uuid', $this->attachment);
        }

        $this->images = $query->orderBy('order_column')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.images');
    }
}
