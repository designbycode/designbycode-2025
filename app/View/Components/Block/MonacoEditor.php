<?php

namespace App\View\Components\Block;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MonacoEditor extends Component
{
    public string $content;
    public string $language;
    public string $placeholder;
    public bool $editable;
    public bool $showCopyButton;
    public string $fontSize;
    public string $minHeight;
    public string $theme;
    public ?string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string  $content = '',
        string  $language = 'html',
        string  $placeholder = 'Start typing here...',
        bool    $editable = true,
        bool    $showCopyButton = true,
        string  $fontSize = '16px',
        string  $minHeight = 'auto',
        string  $theme = 'blackboard',
        ?string $id = null
    )
    {
        $this->content = $content;
        $this->language = $language;
        $this->placeholder = $placeholder;
        $this->editable = $editable;
        $this->showCopyButton = $showCopyButton;
        $this->fontSize = $fontSize;
        $this->minHeight = $minHeight;
        $this->theme = $theme;
        $this->id = $id ?? 'monaco-' . uniqid();
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.block.monaco-editor');
    }
}
