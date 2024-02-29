<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
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
        $user = User::query()->where('username', 'test')->first();

        return [
            'title' => fake()->text(),
            'content' => fake()->text(),
            'user_id' => $user->id,
            'slug' => fake()->slug(),
        ];
    }
}
