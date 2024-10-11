<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy("created_at", "desc");
        if(isset($request['name']) && !empty($request['name']))
        {
            $categories = $categories->where('name','like', "%".$request['name']."%");
        }
        
        $categories = $categories->paginate(10);
        
        return view('admin.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'slug' => 'required | unique:categories,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'description.required' => 'Vui lòng nhập mô tả danh mục',
            'slug.required' => 'Vui lòng nhập slug',
            'slug.unique' => 'slug đã tồn tại vui lòng nhập slug khác',
            'image.required' => 'Vui lòng upload ảnh lên',
            'image.mimes' => 'Ảnh đã tải lên không đúng định dạng',
            'image.max' => 'Ảnh đã tải lên tối đa 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

     
        $result = Category::create([
            "name" => $request['name'] ?? null,
            "description" => $request['description'] ?? null,
            "image" => $request['image'] ? $request->file('image')->store('images/category', 'public') : null,
            "slug" => $request['slug'] ?? null,
            "tags" => $request['tags'] ?? null,
            "meta_title" => $request['meta_title'] ?? null,
            "meta_keywords" => $request['meta_keywords'] ?? null,
            "meta_description" => $request['meta_description'] ?? null,
            "canonical_url" => $request['canonical_url'] ?? null,
            "created_at" => Carbon::now(),
        ]);

        if($result)
        {
            return redirect()->route('category.index')->with('success', 'Lưu dữ liệu thành công');
        }

         // Nếu đăng nhập thất bại, trả về lỗi
         return redirect()->back()->with('error', 'Đã xảy ra lỗi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'slug' => 'required |'.Rule::unique('categories', 'slug')->ignore($category->id),
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'description.required' => 'Vui lòng nhập mô tả danh mục',
            'slug.required' => 'Vui lòng nhập slug',
            'slug.unique' => 'slug đã tồn tại vui lòng nhập slug khác',
            'image.required' => 'Vui lòng upload ảnh lên',
            'image.mimes' => 'Ảnh đã tải lên không đúng định dạng',
            'image.max' => 'Ảnh đã tải lên tối đa 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->name = $request['name'];
        $category->description = $request['description'];
        $category->slug = $request['slug'];
        $category->tags = $request['tags'];
        $category->meta_title = $request['meta_title'];
        $category->meta_keywords = $request['meta_keywords'];
        $category->meta_description = $request['meta_description'];
        $category->canonical_url = $request['canonical_url'];

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->image = $request['image'] ? $request->file('image')->store('images/category', 'public') : null;
        $category->save();
        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
    
        // Xóa category khỏi database
        $category->delete();
    
        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }
}
