<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['quiz_id', 'user_id', 'score', 'total', 'percentage', 'attempted_at'];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    // Ajouter la relation avec Quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}