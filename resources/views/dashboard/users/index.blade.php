@extends('layouts.dashboard')

@section('title', __('Users'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Users') }}</li>
@endsection

@section('content')

<x-flash-message class="info" />

<div class="table-toolbar mb-3 d-flex justify-content-between">
    <div class="">
        <form action="{{ route('dashboard.users.index') }}" class="d-flex" method="get">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control">
            <button type="submit" class="btn btn-dark ml-2">{{ trans('Search') }}</button>
        </form>
    </div>
    <div class="">
        <a href="{{ route('dashboard.users.create') }}" class="btn btn-sm btn-outline-primary">{{ __('Create') }}</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Roles') }}</th>
                <th colspan="2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ implode(' | ', $user->roles->pluck('name')->toArray()) }}</td>
                <td>
                    @can('users.update')
                    <a href="{{ route('dashboard.users.edit', [$user->id]) }}" class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                    @endcan
                </td>
                <td>
                    @can('users.delete')
                    <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="post">
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

