<?php

namespace App\Livewire\Pages;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('About Us')]
class AboutUsPage extends Component
{
    public function render(): View
    {
        return view('livewire.pages.about-us-page');
    }
}
