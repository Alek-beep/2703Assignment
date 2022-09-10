@extends('layouts.master')

@section('title')
  Projects
@endsection
   
@section('content')
<h1>Projects applied to:</h1>
<h3>Click on a project to navigate to the details page for the project</h3>
<div class="grid-container">
@forelse($projects as $project)
  <a class="grid-item" href="/details/{{$project->project_id}}">Project Name: {{$project->projectName}}<br>Industry Partner: {{$project->partnerName}}</a>
@empty
  <p>No Projects Found.</p>
@endforelse
</div>
@endsection
