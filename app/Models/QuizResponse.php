<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResponse extends Model
{
    protected $table = 'quiz_responses';

    protected $fillable = ['quiz_id', 'user_id', 'question_id', 'answer', 'is_correct'];

    // Relation avec le quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la question
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}