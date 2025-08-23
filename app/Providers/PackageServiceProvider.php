<?php

namespace App\Providers;

use App\Models\Package;
use App\Observers\PackageObserver;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
   
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Package::observe(PackageObserver::class);
    }
}
