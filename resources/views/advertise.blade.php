@extends('layouts.master')

@section('title')
  Advertise
@endsection
   
@section('content')
  <h4>{{$errorMessage}}</h4>
  <form method="post" action="/add_project">
    {{csrf_field()}}
    <h3>Advertise a project:</h3>
    <p>
      <label>Company Name: </label><br>
      <input type="text" name="companyName">
    </p>
    <p>
      <label>Company Location: </label><br>
      <input type="text" name="companyLocation">
    </p>
    <p>
      <label>Project Title: </label><br>
      <input type="text" name="projectTitle">
    </p>
    <p>
      <label>Major: </label><br>
      <input type="text" name="major">
    </p>
    <p>
      <label>Project Description: </label><br>
      <textarea name="projectDescription"></textarea>
    </p>
    <p>
      <label>Number of Students: </label><br>
      <input type="number" name="numberStudents">
    </p>
    <input type="submit" value="Add item">
  </form>
@endsection


