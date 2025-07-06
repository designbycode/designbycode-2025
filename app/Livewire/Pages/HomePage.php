<?php

namespace App\Livewire\Pages;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    #[Title('Home')]
    public function render(): View
    {
        return view('livewire.pages.home-page');
    }
}
