@extends('layouts.dashboard')

@section('title', 'Create User')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Users</li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

<form action="{{ route('dashboard.users.store') }}" method="post">
    @include('dashboard.users._form', [
        'button' => 'Create'
    ])
</form>

@endsection