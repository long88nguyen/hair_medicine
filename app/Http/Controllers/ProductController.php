<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Product::orderBy('created_at', 'desc')->get();
        $categories = Category::get();
        $brands = Brand::get();
        return view('admin.pages.product.index', ['items' => $items,'categories' => $categories,'brands'=> $brands]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.pages.product.create', ['categories' => $categories,'brands'=> $brands]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'slug' => 'required | unique:products,slug',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,wepb|max:2048',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'content' => 'required'
        ],
        [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'description.required' => 'Vui lòng nhập mô tả sản phẩm',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'slug.required' => 'Vui lòng nhập slug',
            'slug.unique' => 'slug đã tồn tại vui lòng nhập slug khác',
            'images.required' => 'Vui lòng upload ảnh lên',
            'images.*.mimes' => 'Ảnh đã tải lên không đúng định dạng',
            'image.*.max' => 'Ảnh đã tải lên tối đa 2MB',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm đã nhập không đúng định dạng',
            'quantity.required' => 'Vui lòng nhập số lượng sản phẩm',
            'content.required' => 'Vui lòng nhập mô tả',
            'quantity.numeric' => 'Số lượng sản phẩm sản phẩm đã nhập không đúng định dạng',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // try {
            // DB::transaction(function () use ($request) {?
                $result = Product::create([
                    "name" => $request['name'] ?? null,
                    "content" => $request['content'] ?? null,
                    "description" => $request['description'] ?? null,
                    "quantity" => $request['quantity'] ?? 0,
                    "category_id" => $request['category_id'] ?? null,
                    "brand_id" => $request['brand_id'] ?? null,
                    "price" => $request['price'] ?? 0,
                    "discount" => $request['discount'] ?? 0,
                    "slug" => $request['slug'] ?? null,
                    "tags" => $request['tags'] ?? null,
                    "meta_title" => $request['meta_title'] ?? null,
                    "meta_keywords" => $request['meta_keywords'] ?? null,
                    "meta_description" => $request['meta_description'] ?? null,
                    "canonical_url" => $request['canonical_url'] ?? null,
                    "created_at" => Carbon::now(),
                    "created_by" => Auth::id(),
                ]);
        
                if (!empty($request['images'])) {
                    $productImage = [];
                    $images = $request->file('images');
                    foreach ($images as $key => $image) {
                        $productImage[] = [
                            'product_id' => $result->id,
                            'url' => $image ? $image->store('images/category', 'public') : null,
                            'is_main' => $key == 0 ? 1 : 0,
                        ];
                    }
                    ProductImage::insert($productImage);
                }

                if($result)
                {
                    return redirect()->route('product.index')->with('success', 'Lưu dữ liệu thành công');
                }
        
                 // Nếu đăng nhập thất bại, trả về lỗi
                 return redirect()->back()->with('error', 'Đã xảy ra lỗi.');
        //     });
        // } catch (\Exception $e) {
        //     // Ghi log lỗi
        //     Log::error('Error saving product or images: ' . $e->getMessage());
        
        //     // Trả về lỗi hoặc xử lý theo cách khác
        //     return redirect()->back()->with('error', 'Đã xảy ra lỗi.');
        // }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product)
    {
        return view('admin.pages.product.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
