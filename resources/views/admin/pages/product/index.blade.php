

@extends('admin.layout2')

@push('header')

@endpush

@section('title')
    Danh sách sản phẩm
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h5>Danh sách sản phẩm</h5>
    <div class="text-end">
        <a href="{{ route('product.create') }}" class="btn btn-primary m-1">+ Thêm mới sản phẩm</a>
    </div>
@endsection