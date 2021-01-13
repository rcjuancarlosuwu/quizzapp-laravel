<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Level;
use App\Models\Problem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProblemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Problem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'level_id'  => Level::factory(),
            'block_id'  => Block::factory(),
            'type'  => $this->faker->randomElement(['text', 'video']),
            'body'  => $this->faker->sentence($nbWords = 3, $variableNbWords = true),
        ];
    }
}
