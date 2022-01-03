@extends('layouts.dashboard')

@section('title', 'Create Category')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Categories</li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

<form action="{{ route('dashboard.categories.store') }}" method="post">
    {{--
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    {{ csrf_field() }}
    --}}
    @csrf
    <input type="hidden" name="secret" value="secret">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group mb-3">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="parent_id">Category Parent</label>
                <select id="parent_id" name="parent_id" class="form-control">
                    <option value="">No Parent</option>
                    @foreach($parents as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="image">Thumbnail</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('dashboard.categories.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </div>

</form>

@endsection