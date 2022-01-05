@extends('layouts.dashboard')

@section('title', 'Create Category')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Categories</li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('dashboard.categories.store') }}" method="post" enctype="multipart/form-data">
    {{--
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    {{ csrf_field() }}
    --}}
    
    @include('dashboard.categories._form', [
        'button' => 'Create'
    ])

</form>

@endsection