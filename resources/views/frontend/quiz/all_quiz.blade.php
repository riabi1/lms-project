@extends('frontend.master')


@section('home') 
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Quiz</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
  
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Quiz Title</th>
                            <th>Description</th>
                            <th>Course Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quizzes as $key => $quiz)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ $quiz->description ?? 'N/A' }}</td>
                            <td>{{ $quiz->course ? $quiz->course->course_name : 'No Course' }}</td>
                            <td>
                                <a href="{{ route('student.quiz.view', $quiz->id) }}" class="btn btn-primary">View Quiz</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No quizzes available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection