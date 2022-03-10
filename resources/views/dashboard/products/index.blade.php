@extends('layouts.dashboard')

@section('title', 'Products')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')

@php
    $message = 'Hello!';
@endphp

<x-flash-message />

<div class="table-toolbar mb-3 d-flex justify-content-between">
    <div class="">
        <form action="{{ route('dashboard.products.index') }}" class="d-flex" method="get">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control">
            <button type="submit" class="btn btn-dark ml-2">Search</button>
        </form>
    </div>
    <div class="">
        @can('create', App\Models\Product::class)
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-sm btn-outline-primary">Create</a>
        @endcan
        <a href="{{ route('dashboard.products.trash') }}" class="btn btn-sm btn-outline-success">Trash</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Qty.</th>
                <th>SKU</th>
                <th>Status</th>
                <th>Created At</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    <img src="{{ $product->image_url }}" height="60">
                </td>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category_id }}</td>
                <td>{{ $product->price }}
                    @if ($product->compare_price)
                    | <del>{{ $product->compare_price }}</del>
                    @endif
                </td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->status }}</td>
                <td>{{ $product->created_at }}</td>
                <td>
                    @can('update', $product)
                    <a href="{{ route('dashboard.products.edit', [$product->id]) }}" class="btn btn-sm btn-outline-success">Edit</a>
                    @endcan
                </td>
                <td>
                    @can('delete', $product)
                    <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
