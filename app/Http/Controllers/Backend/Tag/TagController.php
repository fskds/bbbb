<?php

namespace App\Http\Controllers\Backend\Tag;

use App\Models\Backend\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NavCreateRequest;
use App\Http\Requests\NavUpdateRequest;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.tag.tag');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NavCreateRequest $request)
    {
        $data = $request->all();
        if (Tag::create($data)) {
            return response()->json(['code' => 1, 'msg' => '添加tag成功']);
        }
        return response()->json(['code' => 2, 'msg' => '系统错误']);
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
        $tag  = Tag::findOrFail($id);
        return view('backend.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(NavUpdateRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $data       = $request->all();
        if ($tag->update($data)) {
            return response()->json(['code' => 1, 'msg' => '更新tag成功']);
        }
        return response()->json(['code' => 2, 'msg' => '系统错误']);
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
        $tag = Tag::find($ids[0]);
        if (!$tag) {
            return response()->json(['code' => 7, 'msg' => 'tag不存在']);
        }
        // 如果有子权限，则禁止删除
        if ($tag->delete()) {
            return response()->json(['code' => 1, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 2, 'msg' => '删除失败']);
    }
    public function restore(Request $request)
    {
        $ids = $request->get('ids');
		$tag = Tag::withTrashed()->find($ids);
		
		if ($tag->restore()){
			return response()->json(['code' => 1, 'msg' => '恢复导航成功']);
		}
        return response()->json(['code' => 2, 'msg' => '恢复导航失败']);
    }
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'tag':
                $query = new Tag();

                break;
			case 'hasdel':
                $query = new Tag();
				$query = $query->onlyTrashed();
                break;	
            default:
                $query = new Tag();
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
