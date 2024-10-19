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
    <h5>Thêm mới sản phẩm</h5>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
        @method('POST')
        @csrf
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="">Tên sản phẩm</label>
                    <input type="text" class="form-control" placeholder="Nhập tên sản phẩm..." id="title"
                        onkeyup="ChangeToSlug();" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="">Slug</label>
                    <input type="text" class="form-control" placeholder="Slug..." id="slug" name="slug"
                        value="{{ old('slug') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="">Mô tả</label>
                    <textarea id="" class="form-control" placeholder="Nhập mô tả..." name="description"
                        value="{{ old('description') }}"></textarea>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group mt-2">
                            <label for="">Danh mục</label>
                            <select name="category_id" id="" class="form-control"
                                value = "{{ old('category_id') }}">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mt-2">
                            <label for="">Thương hiệu</label>
                            <select name="brand_id" id="" class="form-control" value = "{{ old('brand_id') }}">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="">Gía sản phẩm</label>
                            <input type="number" class="form-control" name="price" value="{{ old('price') }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="">Số lượng sản phẩm</label>
                            <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="">Discount (%)</label>
                            <input type="number" class="form-control" name="discount" value="{{ old('discount') }}"
                                min="0" max="100">
                        </div>
                    </div>
                </div>



                <!-- <div class="form-group">
                    <label for="">Ảnh</label>
                    <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                        class="form-control">
                    <br>
                    <img id="image-preview" style="max-width: 300px; max-height: 300px; margin-top: 10px;" />
                    <br>
                </div> -->
            </div>
            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="">Tags</label>
                    <input type="text" class="form-control" placeholder="Tags..." name="tags"
                        value="{{ old('tag') }}">
                </div>

                <div class="form-group">
                    <label for="">Meta Title</label>
                    <input type="text" class="form-control" placeholder="Meta Title..." name="meta_title"
                        value="{{ old('meta_title') }}">
                </div>

                <div class="form-group">
                    <label for="">Meta keywords</label>
                    <input type="text" class="form-control" placeholder="Meta keywords..." name="meta_keywords"
                        value="{{ old('meta_keywords') }}">
                </div>

                <div class="form-group">
                    <label for="">Meta description</label>
                    <textarea id="" class="form-control" placeholder="Nhập mô tả..." name="meta_description"
                        value="{{ old('meta_description') }}"></textarea>
                </div>

                <div class="form-group">
                    <label for="">Canonical Url</label>
                    <input type="text" class="form-control" placeholder="Canonical Url..." name="canonical_url"
                        value="{{ old('canonical_url') }}">
                </div>
                <div class="form-group">
                    <label for="">Ảnh sản phẩm</label>
                </div>
            </div>
        </div>
        <div class="">
            <label for="">Chi tiết sản phẩm</label>

            <label class="control-label font-weight-bold" for="tinymce-content">Chi tiết sản phẩm</label>
            <textarea id="tinymce-content" class="form-control form-control-sm tinymce-selector" name="content" rows="6"
                aria-hidden="true"></textarea>
        </div>
        <div class="text-end">
            <button type="submit" class=" btn btn-primary btn-sm m-1">Lưu</button>
            <a href="{{ route('category.index') }}" class="btn border m-1">Quay lại</a>
        </div>
    </form>
@endsection

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        admin.initTinyMCE('textarea.tinymce-selector');
    });
</script>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/admin/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/uploader.js') }}"></script>
@endpush
