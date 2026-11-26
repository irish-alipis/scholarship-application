@extends('layouts.master')

@section('content')
    <h1>Scholarship dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
@endsection