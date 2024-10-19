<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private $imageFolder = '';
    private $largeImageFolder = '';
    private $mediumImageFolder = '';
    private $thumbnailImageFolder = '';
    private $defaultCropSizes = '';
    private $largeCropWidth = 0;
    private $largeCropHeight = 0;
    private $mediumCropWidth = 0;
    private $mediumCropHeight = 0;
    private $smallCropWidth = 0;
    private $smallCropHeight = 0;
    public $ratioRate;
    private $cdnUrl = '';
    protected $imageManager;

    public function __construct()
    {
        $this->imageFolder = '/images' . date("/Y/m/d/");
        $this->largeImageFolder = '/images' . date("/Y/m/d/") . "large/";
        $this->mediumImageFolder = '/images' . date("/Y/m/d/") . "medium/";
        $this->thumbnailImageFolder = '/images' . date("/Y/m/d/") . "thumbnail/";
        $this->cdnUrl = url('/storage/');
        $config = config('app.dimensions');
        // dd($config);
        $this->defaultCropSizes = 'L:' . $config['IMAGE_LARGE_WIDTH'] . 'x' . $config['IMAGE_LARGE_HEIGHT'] . '|M:' . $config['IMAGE_MEDIUM_WIDTH'] . 'x' . $config['IMAGE_MEDIUM_HEIGHT'] . '|S:' . $config['IMAGE_THUMBNAIL_WIDTH'] . 'x' . $config['IMAGE_THUMBNAIL_HEIGHT'];
        $this->ratioRate = [0 => 0, 1 => 1, 2 => 4 / 3, 3 => 3 / 2, 4 => 16 / 9];
    }

    public function uploadImage(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $response = ['status' => false, 'task' => 'upload', 'errors' => []];
        $messages = [
            'attachment.mimes' => 'Hệ thống chỉ chấp nhận tệp tin có định dạng: jpeg,bmp,png,gif,webp,svg',
            'attachment.max' => 'Hệ thống chỉ chấp nhận tệp tin nhỏ hơn 2Mb'
        ];

        //Don't use ->validate() to call ->errors() for ajax
        $validator = Validator::make($request->all(), [
            'attachment' => 'mimes:jpeg,bmp,png,gif,webp,svg|max:2048',
        ], $messages);

        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->get('attachment');
            return response()->json($response);
        }

        if ($request->hasfile('attachment')) {
            $attachment = $request->file('attachment');
            $extension = $request->file('attachment')->getClientOriginalExtension();
            $fileName = $request->attachment->getClientOriginalName() .'-'. time() .'.' . $extension;
            $url = $request->file('attachment')->store('/images' . date("/Y/m/d"), 'public');
            $response['images'][] = '/'.$url;
            $response['status'] = true;
            
        }

        exit(json_encode($response));
    }
}
