@extends('layouts.dashboard')

@section('title', __('Roles'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Roles') }}</li>
@endsection

@section('content')

<x-flash-message class="info" />

<div class="table-toolbar mb-3 d-flex justify-content-between">
    <div class="">
        <form action="{{ route('dashboard.roles.index') }}" class="d-flex" method="get">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control">
            <button type="submit" class="btn btn-dark ml-2">{{ trans('Search') }}</button>
        </form>
    </div>
    <div class="">
        <a href="{{ route('dashboard.roles.create') }}" class="btn btn-sm btn-outline-primary">{{ __('Create') }}</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Permissions #') }}</th>
                <th>{{ __('Users #') }}</th>
                <th colspan="2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ count($role->permissions) }}</td>
                <td>{{ $role->users_count }}</td>
                <td>
                    @can('roles.update')
                    <a href="{{ route('dashboard.roles.edit', [$role->id]) }}" class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                    @endcan
                </td>
                <td>
                    @can('roles.delete')
                    <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

