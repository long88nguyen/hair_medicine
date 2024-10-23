@php
    use Carbon\Carbon;
@endphp

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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Ảnh chính</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Trạng thái</th>
                <th>Thời gian tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->brand->name ?? null }}</td>
                    <td><img src="{{ asset("storage/")}}/{{$item->product_image_main->url ?? null}}" alt="" srcset="" width="100px"></td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ Carbon::parse($item->created_at)->format('H:i:s d/m/Y') }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('product.edit', $item->id ) }}" class="btn btn-primary m-1">Sửa</a>
                            <form action="{{ route('product.destroy', $item->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger m-1" onclick="return confirm('Are you sure you want to delete this category?')">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
@endsection