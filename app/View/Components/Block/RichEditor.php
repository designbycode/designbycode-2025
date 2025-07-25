<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RichEditor extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $content,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.rich-editor');
    }
}
