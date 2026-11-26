@extends('layouts.master')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="box custom-box">
    <div class="box-header"><div class="box-title">Edit {{ $user->name }}</div></div>
    <div class="box-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @method('PUT')
            @include('Admin.users._form')
            <button type="submit" class="ti-btn ti-btn-primary mt-4">Update</button>
            <a href="{{ route('admin.users.index') }}" class="ti-btn ti-btn-light mt-4">Cancel</a>
        </form>
    </div>
</div>
@endsection