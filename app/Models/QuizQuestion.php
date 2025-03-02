<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions'; // Nom de la table

    protected $fillable = ['quiz_id', 'text', 'options', 'correct_answer'];

    // Relation avec le quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Convertir les options en tableau (stockées en JSON)
    protected $casts = [
        'options' => 'array',
    ];
}