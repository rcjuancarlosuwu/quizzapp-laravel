<?php

namespace Database\Factories;

use App\Models\Alternative;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlternativeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Alternative::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question_id' => Question::factory(),
            'alternative' => $this->faker->sentence($nbWords = 3, $variableNbWords = true)
        ];
    }
}
