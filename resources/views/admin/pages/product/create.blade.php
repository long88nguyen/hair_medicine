@extends('admin.layout2')

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
        <div class="text-end">
            <a href="{{ route('category.index') }}" class="btn border m-1">Quay lại</a>
            <button type="submit" class=" btn btn-primary m-1">Lưu</button>

        </div>
        @method('POST')
        @csrf
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group mt-2">
                    <b for="">Tên sản phẩm</b>
                    <input type="text" class="form-control"  id="title"
                        onkeyup="ChangeToSlug();" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group mt-2">
                    <b for="">Slug</b>
                    <input type="text" class="form-control"  id="slug" name="slug"
                        value="{{ old('slug') }}">
                </div>
                <div class="form-group mt-2">
                    <b for="">Mô tả</b>
                    <textarea id="" class="form-control"  name="description"
                        value="{{ old('description') }}"></textarea>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group mt-2">
                            <b for="">Danh mục</b>
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
                            <b for="">Thương hiệu</b>
                            <select name="brand_id" id="" class="form-control" value = "{{ old('brand_id') }}">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mt-2">
                            <b for="">Gía sản phẩm</b>
                            <input type="number" class="form-control" name="price" value="{{ old('price') }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mt-2">
                            <b for="">Số lượng sản phẩm</b>
                            <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mt-2">
                            <b for="">Discount (%)</b>
                            <input type="number" class="form-control" name="discount" value="{{ old('discount') }}"
                                min="0" max="100">
                        </div>
                    </div>
                </div>



                <!-- <div class="form-group mt-2">
                    <b for="">Ảnh</b>
                    <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                        class="form-control">
                    <br>
                    <img id="image-preview" style="max-width: 300px; max-height: 300px; margin-top: 10px;" />
                    <br>
                </div> -->
            </div>
            <div class="col-6 col-md-6">
                <div class="form-group mt-2">
                    <b for="">Tags</b>
                    <input type="text" class="form-control"  name="tags"
                        value="{{ old('tag') }}">
                </div>

                <div class="form-group mt-2">
                    <b for="">Meta Title</b>
                    <input type="text" class="form-control"  name="meta_title"
                        value="{{ old('meta_title') }}">
                </div>

                <div class="form-group mt-2">
                    <b for="">Meta keywords</b>
                    <input type="text" class="form-control"  name="meta_keywords"
                        value="{{ old('meta_keywords') }}">
                </div>

                <div class="form-group mt-2">
                    <b for="">Meta description</b>
                    <textarea id="" class="form-control"  name="meta_description"
                        value="{{ old('meta_description') }}"></textarea>
                </div>

                <div class="form-group mt-2">
                    <b for="">Canonical Url</b>
                    <input type="text" class="form-control" name="canonical_url"
                        value="{{ old('canonical_url') }}">
                </div>
                <div class="form-group mt-2">
                    <b for="">Ảnh sản phẩm</b>
                    <input type="file" name="images[]" multiple class="form-control" />
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <b for="">Chi tiết sản phẩm</b>
            <textarea id="tinymce-content" class="form-control form-control-sm tinymce-selector" name="content" rows="6"
                aria-hidden="true"></textarea>
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
