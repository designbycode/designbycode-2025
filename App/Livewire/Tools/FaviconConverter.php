<?php

namespace App\Livewire\Tools;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class FaviconConverter extends Component
{
    use WithFileUploads;

    public $image;
    public $downloadLink;

    protected array $rules = [
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ];

    public function convert(): void
    {
        $this->validate();

        $sizes = [16, 32, 48, 64, 128, 192];


    }


    public function render(): View
    {
        return view('livewire.tools.favicon-converter');
    }
}
