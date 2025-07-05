<?php

namespace App\Livewire\Tools;

use Illuminate\View\View;
use Livewire\Component;

class FaviconGenerator extends Component
{
   

    public function render(): View
    {
        return view('livewire.tools.favicon-generator');
    }
}
