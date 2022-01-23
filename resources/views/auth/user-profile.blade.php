@extends('layouts.dashboard')

@section('title', 'User Profile')

@section('content')

<x-flash-message />

<p>
    <a href="{{ route('change-password') }}">Change Password</a>
</p>

<form action="{{ route('profile.update') }}" method="post">
    @csrf
    @method('patch')

    <div class="form-group">
        <x-form.input name="name" :value="$user->name" label="Name" />
    </div>
    <div class="form-group">
        <x-form.input name="email" :value="$user->email" label="Email Address" />
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@endsection