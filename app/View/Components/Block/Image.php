<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $url,
        public string $alt
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.image');
    }
}
