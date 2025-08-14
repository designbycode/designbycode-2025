<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SpatieImage extends Component
{
    public $images;

//    public function __construct(
//        public array  $attachment,
//        public string $alt,
//        public string $blockId,
//    )
//    {
//        // Dump the incoming attachment UUIDs first
//        dump('Attachment UUIDs received by component:', $this->attachment);
//
//        // ... (the rest of the debugging code can stay for now)
//        $allMediaForUuids = Media::query()
//            ->whereIn('uuid', $this->attachment)
//            ->get();
//
//        dump('All media for UUIDs:', $allMediaForUuids);
//
//        $this->images = $allMediaForUuids->filter(function ($media) {
//            return $media->getCustomProperty('block_id') === $this->blockId;
//        });
//
//        dump('Filtered images for block_id ' . $this->blockId . ':', $this->images);
//    }


    /**
     * Create a new component instance.
     */
    public function __construct(
        public array  $attachment,
        public string $alt,
        public string $blockId,
        public        $model,
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
        return view('components.block.spatie-image');
    }
}
