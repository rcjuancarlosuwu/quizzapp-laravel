<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\Block;
use App\Models\Code;
use App\Models\Level;
use App\Models\Log;
use App\Models\Problem;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Teacher::create([
            'email' => 'profesor@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        School::factory(5)->create();

        $code = Code::create(['code' => "alV12", 'description' => "Esta sala es para", 'enrollment_codes' => "2018100565K"]);

        $student = Student::create([
            'code_id' => $code->id,
            'enrollment_code' => '2018100565K',
            'nickname' => 'Jurgen',
            'email' => 'e_2018100565K@uncp.edu.pe',
            'school_id' => 1,
            'semester' => 2,
        ]);

        Block::factory(2)->create();

        for ($i = 1; $i <= 3; $i++) {
            $level_id = Level::create(['id' => $i])->id;

            $problem = Problem::create([
                'level_id'  => $level_id,
                'block_id'  => 1,
                'type'  => 'text',
                'body'  => 'texto de nivel ' . $i,
            ]);

            for ($k = 1; $k <= 4; $k++) {
                $question = Question::create([
                    'problem_id'  => $problem->id,
                    'question'  => 'Pregunta ' . $k,
                    'value'  => 4,
                ]);

                $index = rand(1, 5);

                for ($l = 1; $l <= 5; $l++) {
                    $alt_ids[] = Alternative::create([
                        'question_id' => $question->id,
                        'is_correct' => $index == $l ? 1 : 0,
                        'alternative' => 'Alternativa ' . $l
                    ])->id;
                }
            }

            $problem2 = Problem::create([
                'level_id'  => $level_id,
                'block_id'  => 2,
                'type'  => 'video',
                'body'  => 'KKpXpWCTlbo',
            ]);

            for ($k = 1; $k <= 4; $k++) {
                $question = Question::create([
                    'problem_id'  => $problem2->id,
                    'question'  => 'Pregunta ' . $k,
                    'value'  => 4,
                ]);

                $index = rand(1, 5);

                for ($l = 1; $l <= 5; $l++) {
                    $alt_ids[] = Alternative::create([
                        'question_id' => $question->id,
                        'is_correct' => $index == $l ? 1 : 0,
                        'alternative' => 'Alternativa ' . $l
                    ])->id;
                }
            }

            Log::create([
                'student_id' => $student->id,
                'level_id' => $level_id,
                'block_id' => 1,
                'problem_id' => $problem->id,
                'state_key' => "ra" . $i,
                'correct_questions_id' => implode(',', [$problem->id]),
                'ppm'  => 200,
                'duration'  => 120,
            ]);

            Log::create([
                'student_id' => $student->id,
                'level_id' => $level_id,
                'block_id' => 2,
                'problem_id' => $problem2->id,
                'state_key' => "ra" . $i,
                'correct_questions_id' => implode(',', [$problem2->id]),
                'ppm'  => null,
                'duration'  => 120,
            ]);
        }
    }
}
