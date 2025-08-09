<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has an avatar', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $expectedUrl = 'https://ui-avatars.com/api/?name=John+Doe';
    expect($user->avatar())->toBe($expectedUrl);
});
