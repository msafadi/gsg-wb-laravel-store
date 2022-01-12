@extends('layouts.dashboard')

@section('title', 'Create Product')

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

<form action="{{ route('dashboard.products.store') }}" method="post" enctype="multipart/form-data">
    @include('dashboard.products._form', [
        'button' => 'Create'
    ])
</form>

@endsection