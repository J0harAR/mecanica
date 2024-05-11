@extends('layouts.app')

@section('content')
@foreach ($grupos as $grupo)
    {{$grupo}}    
@endforeach



@endsection