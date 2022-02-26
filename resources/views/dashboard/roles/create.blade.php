@extends('layouts.dashboard')

@section('title', 'Create Role')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Roles</li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

<form action="{{ route('dashboard.roles.store') }}" method="post">
    @include('dashboard.roles._form', [
        'button' => 'Create'
    ])
</form>

@endsection