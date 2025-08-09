<?php

namespace App\Livewire\Pages\Packages;

use App\Models\Package;
use Illuminate\View\View;
use Livewire\Component;

class PackageShow extends Component
{
    public object $package;

    public function mount(Package $package): void
    {
        $this->package = $package;

        defer(function () {
            $this->package->visit();
        });
    }

    public function render(): View
    {
        return view('livewire.pages.packages.package-show');
    }
}
