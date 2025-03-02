@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Quiz</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('instructor.quiz.create') }}" class="btn btn-primary px-5">Add Quiz</a>  
            </div>
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
                            <th>Title</th>
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
                            <td>{{ $quiz->course ? $quiz->course->course_name : 'Aucun' }}</td> <!-- Ajusté à course_name -->
                            <td>
                                <a href="{{ route('instructor.quiz.edit', $quiz->id) }}" class="btn btn-info" title="Edit"><i class="lni lni-eraser"></i></a>   
                                <form action="{{ route('instructor.quiz.delete', $quiz->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce quiz ?')" title="Delete"><i class="lni lni-trash"></i></button>
                                </form>
                                <a href="{{ route('instructor.quiz.view', $quiz->id) }}" class="btn btn-primary" title="View Details"><i class="lni lni-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Aucun quiz trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection