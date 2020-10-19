<?php

namespace Orchid\Crud\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchid\Crud\Tests\Models\Post;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(10),
            'description' => $this->faker->realText(100),
            'body' => $this->faker->realText(500),
        ];
    }
}
