@extends('layouts.dashboard')

@section('title', 'Edit Category')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Categories</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<form action="{{ route('dashboard.categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
    <!-- Form Method Spoofing -->
    @method('put')
    {{--
    <input type="hidden" name="_method" value="put">
    --}}

    @include('dashboard.categories._form', [
        'button' => 'Update'
    ])

</form>

@endsection