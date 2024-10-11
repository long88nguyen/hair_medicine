@extends('admin.layout')

@push('header')

@endpush

@section('title')
   Thêm mới sản phẩm
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h1>okk</h1>
@endsection