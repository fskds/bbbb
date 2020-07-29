<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Backend\Admin\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PermissionCreateRequest;
use App\Http\Requests\PermissionUpdateRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.permission.permission');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::where('pid', 0)
                ->with(['child' => function($query){
                    $query->orderBy('sort', 'desc')
                        ->with(['child' => function($query){
                            $query->orderBy('sort', 'desc')
                                ->with(['child' => function($query){
                                    $query->orderBy('sort', 'desc');
                                }]);
                        }]);
                }])->orderBy('sort', 'desc')->get();
        return view('backend.permission.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCreateRequest $request)
    {
        $data = $request->all();
        if (Permission::create($data)) {
            return response()->json(['code' => 1, 'msg' => '添加权限成功']);
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
        $permission  = Permission::findOrFail($id);
        //$permissions = $this->tree();
        $permissions = Permission::where('pid', 0)
                ->with(['child' => function($query){
                    $query->orderBy('sort', 'desc')
                        ->with(['child' => function($query){
                            $query->orderBy('sort', 'desc')
                                ->with(['child' => function($query){
                                    $query->orderBy('sort', 'desc');
                                }]);
                        }]);
                }])->orderBy('sort', 'desc')->get();
        return view('backend.permission.edit', compact('permission', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $data       = $request->all();
        if ($permission->update($data)) {
            return response()->json(['code' => 1, 'msg' => '更新权限成功']);
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
        $permission = Permission::find($ids[0]);
        if (!$permission) {
            return response()->json(['code' => 7, 'msg' => '权限不存在']);
        }
        // 如果有子权限，则禁止删除
        if (Permission::where('pid', $ids[0])->first()) {
            return response()->json(['code' => 5, 'msg' => '存在子权限禁止删除']);
        }
		if(strpos($permission,'system') === false){
			if ($permission->delete()) {
				return response()->json(['code' => 1, 'msg' => '删除成功']);
			}
		}else{return response()->json(['code' => 4, 'msg' => '禁止删除系统项']);}
        return response()->json(['code' => 2, 'msg' => '删除失败']);
    }
    public function restore(Request $request)
    {
        $ids = $request->get('ids');
		$permission = Permission::withTrashed()->find($ids);
		
		if ($permission->restore()){
			return response()->json(['code' => 1, 'msg' => '恢复用户成功']);
		}
        return response()->json(['code' => 2, 'msg' => '恢复用户失败']);
    }
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'permission':
                $query = new Permission();

                break;
			case 'hasdel':
                $query = new Permission();
				$query = $query->onlyTrashed();
                break;	
            default:
                $query = new Permission();
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
