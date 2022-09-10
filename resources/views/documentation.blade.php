@extends('layouts.master')

@section('title')
  Documentation
@endsection
   
@section('content')
    <h2>Documentation: </h2>
    <img src="{{asset('assets/ER-diagram.PNG')}}">
    <h3>Reflection:</h3>
    <p>
        I was able to complete all of the required tasks for the assignment to the best of my ability.
        All of the functionalities of the application work. I had to do the development of the assignment 
        locally as my elf did not let me use composer for some reason. I made sure to test frequently as I went,
        and as a result I never really got stuck at all. I made sure to use the php documentation to solve any problems 
        I came across. What I would like to improve on for assignment two is definately the styling of the website as I did not 
        leave enough time for it, also I would like to submit the assignment on time next time.
        For task 15, I generated an array for each set of preferences. One for first preferences, one for second
        preferences and one for third. Then I generated an array containing all of the project Ids and their maximum student capacities
        ordered by the project id in ascending order. Then I iterated through each project, for each project Id I iterated through each of
        the preference arrays adding students until reaching the maximum number of students. This meant that the students first preferences
        were served first and then the other preferences were considered, satisfying the most students.

    </p>
@endsection