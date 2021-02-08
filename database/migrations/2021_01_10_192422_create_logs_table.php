<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained();
            $table->foreignId('student_id')->constrained(); // delete
            $table->foreignId('level_id')->constrained();
            $table->foreignId('block_id')->constrained();
            $table->foreignId('problem_id')->constrained();
            $table->string('state_key');
            $table->string('correct_questions_id')->nullable();
            $table->integer('ppm')->nullable();
            $table->integer('ppm_points')->nullable();
            $table->integer('duration');
            $table->decimal('score');
            $table->text('appreciation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
