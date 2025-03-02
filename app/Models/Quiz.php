<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['title', 'description', 'course_id', 'instructor_id'];

    // Relation avec le cours
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relation avec l'instructeur
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    
    public function quizQuestions()
{
    return $this->hasMany(QuizQuestion::class);
}
}