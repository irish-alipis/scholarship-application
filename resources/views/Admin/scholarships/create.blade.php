@extends('layouts.master')

@section('title', 'Add Scholarship')
@section('page-title', 'Add Scholarship')

@section('content')
<div class="box custom-box">
    <div class="box-header"><div class="box-title">New Scholarship</div></div>
    <div class="box-body">
        <form method="POST" action="{{ route('admin.scholarships.store') }}">
            @include('Admin.scholarships._form')
            <button type="submit" class="ti-btn ti-btn-primary mt-4">Save</button>
            <a href="{{ route('admin.scholarships.index') }}" class="ti-btn ti-btn-light mt-4">Cancel</a>
        </form>
    </div>
</div>
@endsection