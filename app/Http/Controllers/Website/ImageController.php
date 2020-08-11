<?php

namespace App\Http\Controllers\Website;

use App\Models\Backend\Content\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{

    /**
     * 站点设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($path , $name )
    {
        if(Image::where('name', '=', $name)->where('path', '=', $path)->first()){
            $disk = Storage::disk('upload');
            $exists = $disk->exists($name);
            if ($exists) {
                    return response()->json('文件不存在');
            }
            return response()->file(storage_path('/app/upload/'.$path.'/'.$name));
        }else{
            return response()->json('文件不存在');
        }
    }
}
	//	return response()->json($data);
