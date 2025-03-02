<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResponse;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentQuizController extends Controller
{
    public function StudentQuiz()
    {
        $user_id = Auth::id();
        $purchased_course_ids = \App\Models\Order::where('user_id', $user_id)->pluck('course_id')->toArray();
        $quizzes = Quiz::with('course')
            ->whereIn('course_id', $purchased_course_ids)
            ->orWhereNull('course_id')
            ->get();
        return view('frontend.quiz.all_quiz', compact('quizzes'));
    }

public function StudentQuizView($id)
{
    $quiz = Quiz::with('quizQuestions')->findOrFail($id);
    $user_id = Auth::id();

    if ($quiz->course_id) {
        $hasPurchased = \App\Models\Order::where('user_id', $user_id)
            ->where('course_id', $quiz->course_id)
            ->exists();
        if (!$hasPurchased) {
            return redirect()->route('student.quiz')->with('error', 'Vous devez acheter le cours associé pour accéder à ce quiz.');
        }
    }

    $responses = QuizResponse::where('quiz_id', $id)->where('user_id', $user_id)->get();
    $attempts = QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->count();
    $last_attempt = QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->orderBy('attempted_at', 'desc')->first();

    // Calculer le temps restant en secondes (1 minute = 60 secondes)
    $time_left_seconds = 0;
    if ($attempts >= 3 && $last_attempt) {
        $last_attempt_time = \Carbon\Carbon::parse($last_attempt->attempted_at);
        $time_since_last = \Carbon\Carbon::now()->diffInSeconds($last_attempt_time);
        $time_left_seconds = max(0, 60 - $time_since_last); // 60 secondes = 1 minute
    }

    return view('frontend.quiz.view_quiz', compact('quiz', 'responses', 'attempts', 'last_attempt', 'time_left_seconds'));
}

   public function StudentQuizSubmit(Request $request, $id)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour soumettre un quiz.');
    }

    $user_id = Auth::id();
    if (!$user_id) {
        return redirect()->back()->with('error', 'Erreur : utilisateur non identifié.');
    }

    $quiz = Quiz::with('quizQuestions')->findOrFail($id);

    if ($quiz->course_id) {
        $hasPurchased = \App\Models\Order::where('user_id', $user_id)
            ->where('course_id', $quiz->course_id)
            ->exists();
        if (!$hasPurchased) {
            return redirect()->route('student.quiz')->with('error', 'Vous devez acheter le cours associé pour soumettre ce quiz.');
        }
    }

    $attempts = QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->count();

    if ($attempts >= 3) {
        $last_attempt = QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->orderBy('attempted_at', 'desc')->first();
        $time_since_last = Carbon::now()->diffInMinutes($last_attempt->attempted_at);

        if ($time_since_last < 1) { // Changé de 10 à 1 minute
            $minutes_left = 1 - $time_since_last;
            return redirect()->route('student.quiz.view', $id)->with('error', "Vous avez atteint la limite de 3 tentatives. Attendez $minutes_left minute avant de retenter.");
        }
        QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->delete();
        $attempts = 0;
    }

    $answers = $request->input('answers', []);

    QuizResponse::where('quiz_id', $id)->where('user_id', $user_id)->delete();

    $score = 0;
    $total = $quiz->quizQuestions->count();

    foreach ($quiz->quizQuestions as $question) {
        $answer = $answers[$question->id] ?? null;
        $is_correct = $answer === $question->correct_answer;

        QuizResponse::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user_id,
            'question_id' => $question->id,
            'answer' => $answer,
            'is_correct' => $is_correct,
        ]);

        if ($is_correct) {
            $score++;
        }
    }

    $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

    QuizAttempt::create([
        'quiz_id' => $quiz->id,
        'user_id' => $user_id,
        'score' => $score,
        'total' => $total,
        'percentage' => $percentage,
        'attempted_at' => now(),
    ]);

    return redirect()->route('student.quiz.view', $id)->with('success', "Votre score : $score/$total (" . number_format($percentage, 2) . "%)");
}

  public function StudentQuizReset($id)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour réinitialiser un quiz.');
    }

    $user_id = Auth::id();
    $quiz = Quiz::findOrFail($id);
    $attempts = QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->count();

    if ($attempts >= 3) {
        $last_attempt = QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->orderBy('attempted_at', 'desc')->first();
        $time_since_last = Carbon::now()->diffInMinutes($last_attempt->attempted_at);

        if ($time_since_last < 1) { // Changé de 10 à 1 minute
            $minutes_left = 1 - $time_since_last;
            return redirect()->route('student.quiz.view', $id)->with('error', "Vous avez atteint la limite de 3 tentatives. Attendez $minutes_left minute avant de retenter.");
        }
        QuizAttempt::where('quiz_id', $id)->where('user_id', $user_id)->delete();
    }

    QuizResponse::where('quiz_id', $id)->where('user_id', $user_id)->delete();

    return redirect()->route('student.quiz.view', $id)->with('success', 'Vos réponses ont été réinitialisées. Vous pouvez retenter le quiz.');
}

public function StudentQuizAttempts()
{
    $user_id = Auth::id();
    $attempts = QuizAttempt::with(['quiz.course']) // Charger quiz et son cours
        ->where('user_id', $user_id)
        ->orderBy('attempted_at', 'desc')
        ->get()
        ->groupBy(function ($attempt) {
            return $attempt->quiz->course ? $attempt->quiz->course->course_name : 'Sans cours';
        });

    return view('frontend.quiz.quiz_attempts', compact('attempts'));
}
}