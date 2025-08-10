<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::factory()
            ->state(new Sequence(fn($sequence) => ['created_at' => now()->subDays($sequence->index), 'updated_at' => now()->subDays($sequence->index)]))
            ->times(3)->create();
    }
}
