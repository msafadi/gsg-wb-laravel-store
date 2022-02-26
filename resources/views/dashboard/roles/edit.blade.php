@extends('layouts.dashboard')

@section('title', __('Edit Role'))

@section('breadcrumb')
@parent
<li class="breadcrumb-item">{{ __('Roles') }}</li>
<li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')

<form action="{{ route('dashboard.roles.update', $role->id) }}" method="post">
    @method('put')

    @include('dashboard.roles._form', [
        'button' => 'Update'
    ])

</form>

@endsection