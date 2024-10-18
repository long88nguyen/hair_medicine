@extends('admin.layout')

@push('header')

@endpush

@section('title')
Thêm mới thương hiệu
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<h5>Thêm mới thương hiệu</h5>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul >
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('brand.store') }}" method="post" enctype="multipart/form-data"> 
    @method('POST')
    @csrf
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="">Tên thương hiệu</label>
                <input type="text" class="form-control" placeholder="Nhập tên thương hiệu..."
                     name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="">Ảnh</label>
                <br>
                <label for="image" role="button" class = "btn border btn-sm"><i class="fa-solid fa-cloud-arrow-up text-primary me-1"></i>Tải ảnh lên</label>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                    class="form-control" hidden>
                <br>
                <img id="image-preview" style="max-width: 300px; max-height: 300px; margin-top: 10px;" class="img-thumbnail"/>
                <br>
            </div>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class=" btn btn-primary btn-sm m-1">Lưu</button>
        <a href="{{ route('brand.index') }}" class="btn border m-1">Quay lại</a>
    </div>
</form>
@endsection

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('image-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>