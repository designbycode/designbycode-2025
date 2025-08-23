<?php

namespace App\Livewire\Pages\Packages;

use App\Models\Package;
use Illuminate\View\View;
use Livewire\Component;

class Chunk extends Component
{
    public array $ids = [];

    public function mount(array $ids): void
    {
        $this->ids = $ids;
    }

    public function render(): View
    {
        return view('livewire.pages.packages.chunk', [
            'packages' => Package::query()
                ->orderByRaw('FIELD(id, ' . implode(',', $this->ids) . ')')
                ->whereIn('id', $this->ids)
                ->with('user')
                ->withTotalVisitCount()
                ->get(),
        ]);
    }
}
