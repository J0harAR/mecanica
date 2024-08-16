@extends('layouts.app')

@section('content')

@if ($mensaje)
    <h2>{{$mensaje}}</h2>

@endif
@endsection