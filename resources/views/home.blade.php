@extends('layouts.master')

@section('title')
  Home
@endsection
   
@section('content')
<h3>Advertised projects: </h3>
<div class="grid-container">
@forelse($projects as $project)
  <a class="grid-item" href="/details/{{$project->id}}">Project Name: {{$project->projectName}}<br>Industry Partner: {{$project->partnerName}}
  <br>Number of applications: {{$projectCount[$loop->index]->applicationCount}}</a>
@empty
  <p>No Projects Found.</p>
@endforelse
</div>
@endsection


