<?php

namespace Database\Factories;

use App\Models\Post;
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
            'user_id' => 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence,
            'content' => [
                [
                    'type' => 'markdown',
                    'data' => [
                        'content' => "\n\n\n### Tags  \nnpm, pnpm, Yarn, Bun, JavaScript, package managers, Node.js, web development"
                    ],
                ],
            ],
        ];
    }
}
