@extends('layouts.master')

@section('title')
  Students Applied
@endsection
   
@section('content')
  <h1>Student Applicants:</h1>
  <h3>Click a student to display the projects to which they have applied</h3>
  <div class="grid-container">
    @forelse($students as $student)
     <a class="grid-item" href="/projects/{{$student->student_id}}/">First Name: {{$student->firstName}}<br>Last Name: {{$student->lastName}}</a>
    @empty
    <p>No Students Found.</p>
    @endforelse
  </div>
@endsection


