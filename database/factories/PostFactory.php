<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence;

        return [
            'user_id' => User::factory()->create(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence,
            'live' => true,
            'published_at' => Carbon::now(),
            'content' => [
                [
                    'type' => 'markdown',
                    'data' => [
                        'content' => "\n\n\n### Tags  \nnpm, pnpm, Yarn, Bun, JavaScript, package managers, Node.js, web development",
                    ],
                ],
            ],
        ];
    }
}
