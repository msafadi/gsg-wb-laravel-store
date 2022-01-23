@extends('layouts.dashboard')

@section('title', 'Change Password')

@section('content')

<x-flash-message />

<form action="{{ route('change-password.update') }}" method="post">
    @csrf
    @method('put')

    <div class="form-group">
        <x-form.input type="password" name="password" label="Current Password" />
    </div>
    <div class="form-group">
        <x-form.input type="password" name="new_password" label="New Password" />
    </div>
    <div class="form-group">
        <x-form.input type="password" name="new_password_confirmation" label="Confirm New Password" />
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Change Password</button>
    </div>
</form>

@endsection