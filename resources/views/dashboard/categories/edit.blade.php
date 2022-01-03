@extends('layouts.dashboard')

@section('title', 'Edit Category')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Categories</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<form action="{{ route('dashboard.categories.update', $category->id) }}" method="post">
    @csrf
    <!-- Form Method Spoofing -->
    @method('put')
    <input type="hidden" name="_method" value="put">

    <div class="row">
        <div class="col-md-8">
            <div class="form-group mb-3">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" value="{{ $category->name }}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="parent_id">Category Parent</label>
                <select id="parent_id" name="parent_id" class="form-control">
                    <option value="">No Parent</option>
                    @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" @if($parent->id == $category->parent_id) selected @endif>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control">{{ $category->description }}</textarea>
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