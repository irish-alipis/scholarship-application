@extends('layouts.master')

@section('title', 'Edit Scholarship')
@section('page-title', 'Edit Scholarship')

@section('content')
<div class="box custom-box">
    <div class="box-header"><div class="box-title">Edit {{ $scholarship->name }}</div></div>
    <div class="box-body">
        <form method="POST" action="{{ route('admin.scholarships.update', $scholarship) }}">
            @method('PUT')
            @include('Admin.scholarships._form')
            <button type="submit" class="ti-btn ti-btn-primary mt-4">Update</button>
            <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="ti-btn ti-btn-light mt-4">Cancel</a>
        </form>
    </div>
</div>
@endsection