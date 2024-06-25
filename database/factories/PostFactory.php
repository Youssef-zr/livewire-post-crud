<?php

namespace Database\Factories;

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

        $title = fake()->title() . str()->random(20);
        $status =  ['draft', 'published', 'scheduled', 'archived', 'inactive'];

        return [
            "title" => $title,
            "slug" => str()->slug($title),
            "content" => fake()->paragraph(),
            "mini_description" => fake()->paragraph(),
            "status" => $status[rand(0, 4)],
        ];
    }
}
