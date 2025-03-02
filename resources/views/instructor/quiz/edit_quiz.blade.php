@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Quiz</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('instructor.all.quiz') }}" class="btn btn-secondary px-5">Back to All Quiz</a>  
            </div>
        </div>
    </div>
  
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('instructor.quiz.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Quiz Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $quiz->title) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $quiz->description) }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="course_id" class="form-label">Select Course (Optional)</label>
                    <select name="course_id" id="course_id" class="form-control">
                        <option value="">No Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ $quiz->course_id == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>

                <h4>Questions</h4>
                <div id="questions">
                    @foreach ($quiz->quizQuestions as $index => $question)
                    <div class="question-block mb-3">
                        <div class="form-group">
                            <label>Question Text</label>
                            <input type="text" name="questions[{{ $index }}][text]" class="form-control" value="{{ $question->text }}" required>
                        </div>
                        <div class="form-group">
                            <label>Options (comma-separated, e.g., A,B,C,D)</label>
                            <input type="text" name="questions[{{ $index }}][options]" class="form-control" value="{{ implode(',', $question->options ?? []) }}" placeholder="A,B,C,D">
                        </div>
                        <div class="form-group">
                            <label>Correct Answer</label>
                            <input type="text" name="questions[{{ $index }}][correct_answer]" class="form-control" value="{{ $question->correct_answer }}" required>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary mb-3" id="add-question">Add Another Question</button>
                <button type="submit" class="btn btn-primary">Update Quiz</button>
            </form>
        </div>
    </div>
</div>

<script>
    let questionCount = {{ count($quiz->quizQuestions) }};
    document.getElementById('add-question').addEventListener('click', function() {
        const questionsDiv = document.getElementById('questions');
        const newQuestion = `
            <div class="question-block mb-3">
                <div class="form-group">
                    <label>Question Text</label>
                    <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Options (comma-separated, e.g., A,B,C,D)</label>
                    <input type="text" name="questions[${questionCount}][options]" class="form-control" placeholder="A,B,C,D">
                </div>
                <div class="form-group">
                    <label>Correct Answer</label>
                    <input type="text" name="questions[${questionCount}][correct_answer]" class="form-control" required>
                </div>
            </div>`;
        questionsDiv.insertAdjacentHTML('beforeend', newQuestion);
        questionCount++;
    });
</script>
@endsection