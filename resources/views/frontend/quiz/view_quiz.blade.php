@extends('frontend.master')

@section('home') 

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.quiz') }}">All Quiz</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $quiz->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
  
    <div class="card">
        <div class="card-body">
            <h3>{{ $quiz->title }}</h3>
            <p><strong>Description:</strong> {{ $quiz->description ?? 'No description provided.' }}</p>
            <p><strong>Course:</strong> {{ $quiz->course ? $quiz->course->course_name : 'No course associated' }}</p>
            <p><strong>Tentatives utilisées :</strong> {{ $attempts }}/3</p>

            @if ($attempts >= 3 && $last_attempt)
                <div id="time-left" class="alert alert-warning">
                    Temps restant avant nouvelle tentative : <span id="countdown">{{ floor($time_left_seconds / 60) }}:{{ str_pad($time_left_seconds % 60, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <h4>Questions</h4>
            <div id="quiz-form-container">
                <form action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST">
                    @csrf
                    @foreach ($quiz->quizQuestions as $index => $question)
                        <div class="mb-3">
                            <p><strong>Question {{ $index + 1 }}:</strong> {{ $question->text }}</p>
                            @if ($question->options)
                                @foreach ($question->options as $option)
                                    <div class="form-check">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="form-check-input quiz-input" {{ $attempts >= 3 ? 'disabled' : '' }} required>
                                        <label class="form-check-label">{{ $option }}</label>
                                    </div>
                                @endforeach
                            @else
                                <input type="text" name="answers[{{ $question->id }}]" class="form-control quiz-input" {{ $attempts >= 3 ? 'disabled' : '' }} required>
                            @endif
                        </div>
                    @endforeach
                    <button type="submit" id="submit-btn" class="btn btn-primary" {{ $attempts >= 3 ? 'style="display:none;"' : '' }}>Submit Answers</button>
                </form>
            </div>

            @if (!$responses->isEmpty())
                <div>
                    @foreach ($responses as $index => $response)
                        <div class="mb-3">
                            <p><strong>Question {{ $index + 1 }}:</strong> {{ $response->question->text }}</p>
                            <p><strong>Votre réponse:</strong> {{ $response->answer }}</p>
                            <p><strong>Réponse correcte:</strong> {{ $response->question->correct_answer }}</p>
                            <p><strong>Statut:</strong> {{ $response->is_correct ? 'Correct' : 'Incorrect' }}</p>
                        </div>
                    @endforeach
                    @php
                        $score = $responses->where('is_correct', true)->count();
                        $total = $responses->count();
                        $percentage = ($total > 0) ? ($score / $total) * 100 : 0;
                    @endphp
                    <div class="alert alert-info">
                        Votre score : {{ $score }}/{{ $total }} ({{ number_format($percentage, 2) }}%)
                    </div>
                    @if ($attempts < 3)
                        <a href="{{ route('student.quiz.reset', $quiz->id) }}" class="btn btn-warning" onclick="return confirm('Voulez-vous vraiment réinitialiser vos réponses et retenter le quiz ?')">Retenter le quiz</a>
                    @endif
                </div>
            @endif

            @if ($attempts > 0)
                <h4>Historique des tentatives</h4>
                @php
                    $attempts_history = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', Auth::id())->orderBy('attempted_at', 'desc')->get();
                @endphp
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Score</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attempts_history as $attempt)
                            <tr>
                                <td>{{ $attempt->attempted_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $attempt->score }}/{{ $attempt->total }}</td>
                                <td>{{ number_format($attempt->percentage, 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <a href="{{ route('student.quiz') }}" class="btn btn-secondary mt-3">Back to All Quiz</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let timeLeft = {{ $time_left_seconds }};
        const countdownElement = document.getElementById('countdown');
        const formInputs = document.querySelectorAll('.quiz-input'); // Sélectionner par classe
        const submitButton = document.getElementById('submit-btn');

        if (timeLeft > 0 && countdownElement) {
            console.log('Compte à rebours démarré avec :', timeLeft, 'secondes');
            function updateCountdown() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

                if (timeLeft > 0) {
                    timeLeft--;
                    console.log('Temps restant :', timeLeft);
                } else {
                    console.log('Temps écoulé, réactivation des champs');
                    formInputs.forEach(input => {
                        input.removeAttribute('disabled');
                        console.log('Champ activé :', input);
                    });
                    submitButton.style.display = 'block';
                    document.getElementById('time-left').style.display = 'none';
                }
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        } else {
            console.log('Pas de compte à rebours, timeLeft =', timeLeft);
        }
    });
</script>
@endsection