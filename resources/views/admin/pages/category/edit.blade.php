@extends('admin.layout2')

@push('header')

@endpush

@section('title')
Chỉnh sửa danh mục
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<h5>Chỉnh sửa danh mục</h5>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('category.update', $category->id) }}" method="post" enctype="multipart/form-data"> 
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="">Tên danh mục</label>
                <input type="text" class="form-control" placeholder="Nhập tên danh mục..." id="title"
                    onkeyup="ChangeToSlug();" name="name" value="{{ $category->name }}">
            </div>
            <div class="form-group">
                <label for="">Slug</label>
                <input type="text" class="form-control" placeholder="Slug..." id="slug" name="slug"
                    value="{{ $category->slug }}">
            </div>
            <div class="form-group mt-2">
                <label for="">Mô tả</label>
                <textarea id="" class="form-control" placeholder="Nhập mô tả..." name="description"
                    value="{{ $category->description }}">{{ $category->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="">Ảnh</label>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                    class="form-control">
                <br>
                <img id="image-preview" src="{{ asset('storage/'.$category->image) }}" style="max-width: 300px; max-height: 300px; margin-top: 10px;" />
                <br>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="form-group">
                <label for="">Tags</label>
                <input type="text" class="form-control" placeholder="Tags..." 
                    name="tags" value="{{ $category->tag }}">
            </div>

            <div class="form-group">
                <label for="">Meta Title</label>
                <input type="text" class="form-control" placeholder="Meta Title..." 
                    name="meta_title" value="{{ $category->meta_title }}">
            </div>

            <div class="form-group">
                <label for="">Meta keywords</label>
                <input type="text" class="form-control" placeholder="Meta keywords..." 
                    name="meta_keywords" value="{{ $category->meta_keywords }}">
            </div>

            <div class="form-group">
                <label for="">Meta description</label>
                <textarea id="" class="form-control" placeholder="Nhập mô tả..." name="meta_description"
                    value="{{ $category->meta_description }}">{{ $category->meta_description }}</textarea>
            </div>

            <div class="form-group">
                <label for="">Canonical Url</label>
                <input type="text" class="form-control" placeholder="Canonical Url..." 
                    name="canonical_url" value="{{ $category->canonical_url }}">
            </div>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class=" btn btn-primary m-1">Lưu</button>
        <a href="{{ route('category.index') }}" class="btn border m-1">Quay lại</a>
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