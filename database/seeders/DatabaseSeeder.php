<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\Attempts;
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
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Level::factory(3)->create();
        Block::factory(2)->create();

        Teacher::create([
            'name' => 'David Hurtado Tiza',
            'email' => 'dahuti.epg7@gmail.com',
            'password' => Hash::make('DHTG7'),
        ]);
        Teacher::create([
            'name' => 'Miguel Ángel Travezaño Aldana',
            'email' => 'migueltravezano@gmail.com',
            'password' => Hash::make('MATNO'),
        ]);
        Teacher::create([
            'name' => 'Sonia Amandy Sinche Charca',
            'email' => 'amandyta1012@gmail.com',
            'password' => Hash::make('SAS12'),
        ]);

        $facultades = require 'facultades.php';
        for ($i = 0; $i < count($facultades); $i++) {
            School::create(['school' => $facultades[$i]]);
        }

        $code = Code::create(['code' => "alV12", 'description' => "Esta sala es para", 'enrollment_codes' => "2018100565K"]);

        Attempts::create([
            'student_id' => Student::create([
                'code_id' => $code->id,
                'enrollment_code' => '2018100565K',
                'nickname' => 'Jurgen',
                'email' => 'e_2018100565K@uncp.edu.pe',
                'school_id' => 1,
                'semester' => 2,
            ])->id,
            'attempt' => 1,
            'xp' => 0
        ]);

        $problems = require 'problems.php';

        for ($i = 0; $i < count($problems); $i++) {
            $problem = Problem::create([
                'level_id'  => $problems[$i]['level_id'],
                'block_id'  => $problems[$i]['block_id'],
                'type'  => $problems[$i]['type'],
                'body'  => $problems[$i]['body'],
            ]);

            for ($j = 0; $j < count($problems[$i]['questions']); $j++) {
                $question = Question::create([
                    'problem_id'  => $problem->id,
                    'question'  => $problems[$i]['questions'][$j]['question'],
                    'value'  => $problems[$i]['questions'][$j]['value'],
                ]);

                for ($k = 0; $k < count($problems[$i]['questions'][$j]['alternatives']); $k++) {
                    Alternative::create([
                        'question_id' => $question->id,
                        'is_correct' => $problems[$i]['questions'][$j]['alternatives'][$k]['is_correct'],
                        'alternative' => $problems[$i]['questions'][$j]['alternatives'][$k]['alternative'],
                    ]);
                }
            }
        }
    }
}
