@php
    use Carbon\Carbon;
@endphp


@extends('admin.layout')

@push('header')

@endpush

@section('title')
    Danh sách danh mục
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h5>Danh sách danh mục</h5>
    <div class="form-search">
        <form action="{{ route('category.index') }}" method="get">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên ...." value="{{ request()->name }}"></input>
                    </div>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
                </div>
            </div>
        </form>
       
    </div>
    <div class="text-end">
        <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm m-1">+ Thêm mới danh mục</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Ảnh</th>
                <th>Mô tả</th>
                <th>Thời gian tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $key => $category)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td><img src="{{ asset("storage/".$category->image ) }}" alt="" srcset="" width="100px"></td>
                    <td>{{ $category->description }}</td>
                    <td>{{ Carbon::parse($category->created_at)->format('H:i:s d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm m-1">Sửa</button>
                        <button class="btn btn-danger btn-sm m-1">Xóa</button>
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
@endsection