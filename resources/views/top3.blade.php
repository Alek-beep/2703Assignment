@extends('layouts.master')

@section('title')
  Top 3
@endsection
   
@section('content')
<h1 style="text-align:center">The top 3 industry partners by order of amount of projects advertised</h1>
<div class="grid-container-top3">
@forelse($partners as $partner)
  @if ($loop->index == 3)
    @break
  @endif
  <a class="grid-item-top3">Partner Name: {{$partner->partnerName}}<br>Number of Projects: {{$partner->numberProjects}}</a>
@empty
  <p>No Projects Found.</p>
@endforelse
</div>
@endsection


