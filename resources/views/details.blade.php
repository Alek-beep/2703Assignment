@extends('layouts.master')

@section('title')
  Details
@endsection
   
@section('content')
<h1 style="text-align:center">{{$errorMessage}}</h1>
<h3>Project Details for {{$projectDetails[0]->projectName}}: </h3>
<ul>
  <li><h3>Project Name: </h3>{{$projectDetails[0]->projectName}}</li>
  <li><h3>Field: </h3>{{$projectDetails[0]->major}}</li>
  <li><h3>Description: </h3>{{$projectDetails[0]->description}}</li>
  <li><h3>Total number of students needed: </h3>{{$projectDetails[0]->numberStudents}}</li>
  <li><h3>Industry partner: </h3>{{$partnerName}}</li>
  <li>
  <h3>Student Applicants: </h3>
  <h4>Click a student to view justification</h4>
  <div class="grid-container">
    @forelse($students as $student)
     <a class="grid-item" href="/justification/{{$student->firstName}}/{{$student->lastName}}/{{$projectDetails[0]->id}}">First Name: {{$student->firstName}}<br>Last Name: {{$student->lastName}}</a>
    @empty
    <p>No Students Found.</p>
    @endforelse
  </div>
  </li>
</ul>
<hr>
<div class="grid-container">
  <form method="post" action="{{url('/update_project')}}">
    {{csrf_field()}}
    <h3>Edit project:</h3>
      <input type="hidden" name="id" value="{{$projectDetails[0]->id}}">
    <p>
      <label>Project Title: </label><br>
      <input type="text" name="projectTitle" value="{{$projectDetails[0]->projectName}}">
    </p>
    <p>
      <label>Major: </label><br>
      <input type="text" name="major" value="{{$projectDetails[0]->major}}">
    </p>
    <p>
      <label>Project Description: </label><br>
      <textarea name="projectDescription">{{$projectDetails[0]->description}}</textarea>
    </p>
    <p>
      <label>Number of Students: </label><br>
      <input type="number" name="numberStudents" value="{{$projectDetails[0]->numberStudents}}">
    </p>
    <input type="submit" value="Update Item">
  </form>

  <form method="post" action="{{url('/delete_project')}}">
    {{csrf_field()}}
    <h3>Delete project:</h3>
    <input type="hidden" name="id" value="{{$projectDetails[0]->id}}">
    <input type="submit" value="Delete Item">
  </form>

  <form method="post" action="{{url('/add_application')}}">
    {{csrf_field()}}
    <h3>Apply to Work on this Project:</h3>
      <input type="hidden" name="id" value="{{$projectDetails[0]->id}}">
    <p>
      <label>First Name: </label><br>
      <input type="text" name="firstName">
    </p>
    <p>
      <label>Last Name: </label><br>
      <input type="text" name="lastName">
    </p>
    <p>
      <label>Justification: </label><br>
      <textarea name="justification">Enter text here</textarea>
    </p>
    <input type="submit" value="Apply">
  </form>
</div>
@endsection


