<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('question_id');
            $table->string('answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_responses');
    }
}