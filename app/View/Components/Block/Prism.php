<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Prism extends Component
{
    public function __construct(
        public string $language = 'php',
        public ?string $code = null,
        public bool $showCopy = true,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.prism');
    }
}
