<?php

namespace App\Http\Controllers\Backend\Content;

use App\Models\Backend\Content\Nav;
use App\Models\Backend\Content\Column;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.column.column');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $navs = Nav::where('pid', 0)
                ->with(['child' => function($query){
                    $query->orderBy('sort', 'desc')
                        ->with(['child' => function($query){
                            $query->orderBy('sort', 'desc')
                                ->with(['child' => function($query){
                                    $query->orderBy('sort', 'desc');
                                }]);
                        }]);
                }])->orderBy('sort', 'desc')->get();
        return view('backend.column.create', compact('navs'));
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
        if (Nav::create($data)) {
            return response()->json(['code' => 1, 'msg' => '添加菜单成功']);
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
        $nav  = Nav::findOrFail($id);
        //$permissions = $this->tree();
        $navs = Nav::where('pid', 0)
                ->with(['child' => function($query){
                    $query->orderBy('sort', 'desc')
                        ->with(['child' => function($query){
                            $query->orderBy('sort', 'desc')
                                ->with(['child' => function($query){
                                    $query->orderBy('sort', 'desc');
                                }]);
                        }]);
                }])->orderBy('sort', 'desc')->get();
        return view('backend.nav.edit', compact('nav', 'navs'));
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
        $nav = Nav::findOrFail($id);
        $data       = $request->all();
        if ($nav->update($data)) {
            return response()->json(['code' => 1, 'msg' => '更新菜单成功']);
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
        $nav = Nav::find($ids[0]);
        if (!$nav) {
            return response()->json(['code' => 7, 'msg' => '菜单不存在']);
        }
        // 如果有子权限，则禁止删除
        if (Nav::where('pid', $ids[0])->first()) {
            return response()->json(['code' => 5, 'msg' => '存在子菜单禁止删除']);
        }
        if ($nav->delete()) {
            return response()->json(['code' => 1, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 2, 'msg' => '删除失败']);
    }
    public function restore(Request $request)
    {
        $ids = $request->get('ids');
		$nav = Nav::withTrashed()->find($ids);
		
		if ($nav->restore()){
			return response()->json(['code' => 1, 'msg' => '恢复导航成功']);
		}
        return response()->json(['code' => 2, 'msg' => '恢复导航失败']);
    }
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'column':
                $query = new Column();

                break;
			case 'hasdel':
                $query = new Column();
				$query = $query->onlyTrashed();
                break;	
            default:
                $query = new Column();
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
