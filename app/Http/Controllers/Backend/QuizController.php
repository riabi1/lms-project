<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function InstructorAllQuiz()
    {
        $quizzes = Quiz::where('instructor_id', Auth::user()->id)->get();
        return view('instructor.quiz.all_quiz', compact('quizzes'));
    }

    public function InstructorAddQuiz()
    {
        $courses = Course::where('instructor_id', Auth::user()->id)->get();
        return view('instructor.quiz.add_quiz', compact('courses'));
    }

    public function InstructorStoreQuiz(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'nullable|string', // Changé de array à string
            'questions.*.correct_answer' => 'required|string',
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
            'instructor_id' => Auth::user()->id,
        ]);

        foreach ($request->questions as $questionData) {
            $options = $questionData['options'] ? explode(',', $questionData['options']) : null; // Convertir la chaîne en tableau
            $quiz->quizQuestions()->create([
                'text' => $questionData['text'],
                'options' => $options,
                'correct_answer' => $questionData['correct_answer'],
            ]);
        }

        return redirect()->route('instructor.all.quiz')->with('success', 'Quiz créé avec succès !');
    }

    public function InstructorEditQuiz($id)
    {
        $quiz = Quiz::with('quizQuestions')->where('id', $id)->where('instructor_id', Auth::user()->id)->firstOrFail();
        $courses = Course::where('instructor_id', Auth::user()->id)->get();
        return view('instructor.quiz.edit_quiz', compact('quiz', 'courses'));
    }

    public function InstructorUpdateQuiz(Request $request, $id)
    {
        $quiz = Quiz::where('id', $id)->where('instructor_id', Auth::user()->id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'nullable|string', // Changé de array à string
            'questions.*.correct_answer' => 'required|string',
        ]);

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
        ]);

        $quiz->quizQuestions()->delete();
        foreach ($request->questions as $questionData) {
            $options = $questionData['options'] ? explode(',', $questionData['options']) : null; // Convertir la chaîne en tableau
            $quiz->quizQuestions()->create([
                'text' => $questionData['text'],
                'options' => $options,
                'correct_answer' => $questionData['correct_answer'],
            ]);
        }

        return redirect()->route('instructor.all.quiz')->with('success', 'Quiz mis à jour avec succès !');
    }

    public function InstructorDeleteQuiz($id)
    {
        $quiz = Quiz::where('id', $id)->where('instructor_id', Auth::user()->id)->firstOrFail();
        $quiz->delete();

        return redirect()->route('instructor.all.quiz')->with('success', 'Quiz supprimé avec succès !');
    }

    public function InstructorViewQuiz($id)
{
    $quiz = Quiz::with('quizQuestions')->where('id', $id)->where('instructor_id', Auth::user()->id)->firstOrFail();
    return view('instructor.quiz.view_quiz', compact('quiz'));
}
}