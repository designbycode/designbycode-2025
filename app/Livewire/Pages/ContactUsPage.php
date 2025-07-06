<?php

namespace App\Livewire\Pages;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Contact Us')]
class ContactUsPage extends Component
{
    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.pages.contact-us-page');
    }
}
