<?php

namespace App\Http\Controllers\Backend\Image;

use App\Models\Backend\Content\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.image.image');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.image.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $filename = public_path('upload/'.$request->input('path').'/'.$request->input('name'));
        $newfile_path = $request->input('path').'/'.$request->input('name');
        $file = file_get_contents($filename);

        if (Image::create($data) ) {
            Storage::disk('upload')->put($newfile_path,$file);
            
            $status = 1;
            $message = "成功上传";
        } else {
            $status = 2;
            $message = "文件上传失败，请稍后再尝试";
        }
        $re = unlink($filename);
        return response()->json(['code' => $status, 'msg' => $message ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image  = Image::findOrFail($id);

        return view('backend.image.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $image  = Image::findOrFail($id);
        $data = $request->all();
        $name = $request->input('name');
        if($name == $image->name){
            if ($image->update($data)) {
                $status = 1;
                $message = "成功上传";
            }
        }else{
            $filename = public_path('upload/'.$request->input('path').'/'.$request->input('name'));
            $newfile_path = $image->path.'/'.$image->name;
            $file = file_get_contents($filename);
            Storage::disk('upload')->put($newfile_path,$file);
            $data['name'] = $image->name;
            if ($image->update($data) ) {
                $status = 1;
                $message = "成功上传";
            } else {
                $status = 2;
                $message = "文件上传失败，请稍后再尝试";
            }
            $re = unlink($filename);
        }
        return response()->json(['code' => $status, 'msg' => $message ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['code' => 3, 'msg' => '请选择删除项']);
        }
        $image = Image::find($ids[0]);
        if (!$image) {
            return response()->json(['code' => 7, 'msg' => '图片不存在']);
        }
        // 如果有文章使用，则禁止删除
        if (Image::where('pid', $ids[0])->first()->Article()) {
            return response()->json(['code' => 5, 'msg' => '存在文章引用禁止删除']);
        }
        if ($image->delete()) {
            return response()->json(['code' => 1, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 2, 'msg' => '删除失败']);
    }
    public function restore(Request $request)
    {
        $ids = $request->get('ids');
		$image = Image::withTrashed()->find($ids);
		
		if ($image->restore()){
			return response()->json(['code' => 1, 'msg' => '恢复图片成功']);
		}
        return response()->json(['code' => 2, 'msg' => '恢复图片失败']);
    }
    function upload(Request $request)
    {
        if ($_POST) {
            //上传图片具体操作
            $file_name = $_FILES['file']['name'];
            //$file_type = $_FILES["file"]["type"];
            $file_tmp = $_FILES["file"]["tmp_name"];
            $file_error = $_FILES["file"]["error"];
            $file_size = $_FILES["file"]["size"];
            //$rule = ['jpg', 'jpeg', 'png', 'gif'];
            if ($file_error > 0) { // 出错
                $status = 2;
                $message = $file_error;
            } elseif($file_size > 1048576) { // 文件太大了
                $status = 2;
                $message = "上传文件不能大于1MB";
            } else{
                $image_size = getimagesize($file_tmp);
                $size = $image_size[0]."X".$image_size[1];
                $date = date('Ymd');
                $file_name_arr = explode('.', $file_name);
                $new_file_name = date('YmdHis') .rand(1000,9999) . '.' . $file_name_arr[1];
                $path = public_path('upload/'.$date);
                $file_path = $path .'/'. $new_file_name;
                if (file_exists($file_path)) {
                    $status = 2;
                    $message = "此文件已经存在啦";
                } else {
                    // TODO 判断当前的目录是否存在，若不存在就新建一个!
                    if (!is_dir($path)){mkdir($path,0777);}
                    $upload_result = move_uploaded_file($file_tmp, $file_path); 
                    // 此函数只支持 HTTP POST 上传的文件
                     if ($upload_result) {
                        $status = 1;
                        $message = array('name' => $new_file_name, 'path' => $date, 'size' => $size, 'title' => $file_name_arr[0]);
                    } else {
                        $status = 2;
                        $message = "文件上传失败，请稍后再尝试";
                    }
                }
            }
        } else {
            $status = 6;
            $message = "参数错误";
        }
        return response()->json(['code' => $status, 'msg' => $message]);
    }

    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'image':
                $query = new Image();

                break;
			case 'hasdel':
                $query = new Image();
				$query = $query->onlyTrashed();
                break;	
            default:
                $query = new Image();
                break;
        }
        $res  = $query->get();
        $data = [
            'code'  => 0,
            'msg'   => '正在请求中...',
            'count' => count($res),
            'data'  => $res
        ];
        return response()->json($data);
    }
}
