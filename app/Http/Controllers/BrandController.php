<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::orderBy("created_at", "desc");
        if(isset($request['name']) && !empty($request['name']))
        {
            $brands = $brands->where('name','like', "%".$request['name']."%");
        }
        
        $brands = $brands->paginate(10);
        
        return view('admin.pages.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'name.required' => 'Vui lòng nhập tên thương hiệu',
            'image.required' => 'Vui lòng upload ảnh lên',
            'image.mimes' => 'Ảnh đã tải lên không đúng định dạng',
            'image.max' => 'Ảnh đã tải lên tối đa 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

     
        $result = Brand::create([
            "name" => $request['name'] ?? null,
            "image" => $request['image'] ? $request->file('image')->store('images/brand', 'public') : null,
            "created_at" => Carbon::now(),
        ]);

        if($result)
        {
            return redirect()->route('brand.index')->with('success', 'Lưu dữ liệu thành công');
        }

         // Nếu đăng nhập thất bại, trả về lỗi
         return redirect()->back()->with('error', 'Đã xảy ra lỗi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.pages.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'name.required' => 'Vui lòng nhập tên thương hiệu',
            'image.required' => 'Vui lòng upload ảnh lên',
            'image.mimes' => 'Ảnh đã tải lên không đúng định dạng',
            'image.max' => 'Ảnh đã tải lên tối đa 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $brand->name = $request['name'];

        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->image = $request['image'] ? $request->file('image')->store('images/brand', 'public') : null;
        $brand->save();
        return redirect()->route('brand.index')->with('success', 'brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }
    
        // Xóa brand$brand khỏi database
        $brand->delete();
    
        return redirect()->route('brand.index')->with('success', 'brand deleted successfully');
    }
}
