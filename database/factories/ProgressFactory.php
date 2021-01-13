<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Level;
use App\Models\Progress;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Progress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_id' => Student::factory(),
            'level_id' => Level::factory(),
            'block_id' => Block::factory(),
        ];
    }
}
