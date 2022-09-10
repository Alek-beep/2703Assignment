@extends('layouts.master')

@section('title')
  Project Assignment
@endsection
   
@section('content')
  <div class="grid-container">
  @forelse($projects as $project)
    <a class="grid-item" href="/details/{{$project->project_id}}">Project Id: {{$project->project_id}}<br>Maximum Students: {{$project->numberStudents}}
      @forelse($project->student_id as $student)
        <p> Student Id: {{$student[0]}}<br></p>
      @empty
        <p>No Students</p>
      @endforelse
    </a>
  @empty
    <p>No Projects Found.</p>
  @endforelse
  </div>
@endsection


