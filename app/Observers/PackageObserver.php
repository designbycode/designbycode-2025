<?php

namespace App\Observers;

use App\Models\Package;
use Illuminate\Support\Str;

class PackageObserver
{
    public function creating(Package $package): void
    {
        $package->slug = Str::slug($package->name);
    }

    public function updating(Package $package): void
    {
        if ($package->isDirty('name')) {
            $package->slug = Str::slug($package->name);
        }
    }
}
