@php
    use Carbon\Carbon;
@endphp


@extends('admin.layout')

@push('header')

@endpush

@section('title')
    Danh sách thương hiệu
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h5>Danh sách thương hiệu</h5>
    <div class="form-search">
        <form action="{{ route('brand.index') }}" method="get">
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
        <a href="{{ route('brand.create') }}" class="btn btn-primary btn-sm m-1">+ Thêm mới thương hiệu</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên thương hiệu</th>
                <th>Ảnh</th>
                <th>Thời gian tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $key => $brand)
                <tr>
                    <td class = "text-center align-middle">{{ $key+1 }}</td>
                    <td class = "text-center align-middle">{{ $brand->name }}</td>
                    <td class = "text-center align-middle"><img src="{{ asset("storage/".$brand->image ) }}" alt="" srcset="" width="100px" class = "img-thumbnail"></td>
                    <td class = "text-center align-middle">{{ Carbon::parse($brand->created_at)->format('H:i:s d/m/Y') }}</td>
                    <td class = "text-center align-middle">
                        <a href="{{ route('brand.edit', $brand->id ) }}" class="btn btn-primary btn-sm m-1">Sửa</a>
                        <form action="{{ route('brand.destroy', $brand->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm m-1" onclick="return confirm('Are you sure you want to delete this brand?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
@endsection