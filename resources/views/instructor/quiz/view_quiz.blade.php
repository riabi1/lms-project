@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('instructor.all.quiz') }}">All Quiz</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $quiz->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('instructor.all.quiz') }}" class="btn btn-secondary px-5">Back to All Quiz</a>  
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
  
    <div class="card">
        <div class="card-body">
            <h3>{{ $quiz->title }}</h3>
            <p><strong>Description:</strong> {{ $quiz->description ?? 'Aucune description fournie.' }}</p>
            <p><strong>Course:</strong> {{ $quiz->course ? $quiz->course->course_name : 'Aucun cours associé' }}</p>
            
            <h4>Questions</h4>
            @forelse ($quiz->quizQuestions as $index => $question)
                <div class="mb-3">
                    <p><strong>Question {{ $index + 1 }}:</strong> {{ $question->text }}</p>
                    @if ($question->options)
                        <p><strong>Options:</strong> {{ implode(', ', $question->options) }}</p>
                    @else
                        <p><strong>Options:</strong> Aucune</p>
                    @endif
                    <p><strong>Correct Answer:</strong> {{ $question->correct_answer }}</p>
                </div>
            @empty
                <p>Aucune question trouvée pour ce quiz.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection