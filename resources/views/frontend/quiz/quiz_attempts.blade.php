@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')


<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quiz Attempts</li>
                </ol>
            </nav>
        </div>
    </div>
  
    <div class="card">
        <div class="card-body">
            <h3>Mes tentatives de quiz</h3>
            @if ($attempts->isEmpty())
                <p>Aucune tentative de quiz enregistrée pour le moment.</p>
            @else
                @foreach ($attempts as $course_name => $course_attempts)
                    <h4>{{ $course_name }}</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Quiz</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course_attempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->quiz->title }}</td>
                                        <td>{{ $attempt->attempted_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $attempt->score }}/{{ $attempt->total }}</td>
                                        <td>{{ number_format($attempt->percentage, 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif

            <a href="{{ route('student.quiz') }}" class="btn btn-secondary mt-3">Retour aux quiz</a>
        </div>
    </div>
</div>
@endsection