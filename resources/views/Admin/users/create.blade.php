@extends('layouts.master')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
    <div class="box custom-box">
        <div class="box-header">
            <div class="box-title">New User</div>
        </div>
        <div class="box-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @include('Admin.users._form')
                <button type="submit" class="ti-btn ti-btn-primary mt-4">Save</button>
                <a href="{{ route('admin.users.index') }}" class="ti-btn ti-btn-light mt-4">Cancel</a>
            </form>
        </div>
    </div>
@endsection