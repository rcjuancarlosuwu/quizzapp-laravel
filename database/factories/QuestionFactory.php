<?php

namespace Database\Factories;

use App\Models\Alternative;
use App\Models\Problem;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'problem_id'  => Problem::factory(),
            'alternative_id'  => Alternative::factory(),
            'question'  => $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'value'  => $this->faker->numerify("#"),
        ];
    }
}
