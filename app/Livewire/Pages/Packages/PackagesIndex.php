<?php

namespace App\Livewire\Pages\Packages;

use App\Models\Package;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PackagesIndex extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.pages.packages.packages-index', [
            'packages' => Package::paginate(),
        ]);
    }
}
