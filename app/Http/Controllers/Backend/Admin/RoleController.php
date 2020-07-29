<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Backend\Admin\Role;
use App\Models\Backend\Admin\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.role.role');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        $data = $request->only(['name', 'display_name']);
        if (Role::create($data)) {
            return response()->json(['code' => 1, 'msg' => '添加角色成功']);
        }
        return response()->json(['code' => 0, 'msg' => '系统错误']);
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
        $role = Role::findOrFail($id);
        return view('backend.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $data = $request->only(['name', 'display_name']);
        if ($role->update($data)) {
            return response()->json(['code' => 1, 'msg' => '更新角色成功']);
        }
        return response()->json(['code' => 0, 'msg' => '系统错误']);
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
            return response()->json(['code' => 0, 'msg' => '请选择删除项']);
        }
		// if(in_array(1,$ids)){
			// return response()->json(['code' => 0, 'msg' => '不可删除超级管理员']);
		// }else{
			if (Role::destroy($ids)) {
				return response()->json(['code' => 1, 'msg' => '删除成功']);
			}
		//}
        return response()->json(['code' => 0, 'msg' => '删除失败']);
    }
	public function restore(Request $request)
    {
        $ids = $request->get('ids');
		$role = Role::withTrashed()->find($ids);
		
		if ($role->restore()){
			return response()->json(['code' => 1, 'msg' => '恢复角色成功']);
		}
        return response()->json(['code' => 0, 'msg' => '恢复角色失败']);
    }
    /**
     * 分配权限
     */
    public function permission(Request $request, $id)
    {
        $role        = Role::findOrFail($id);
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

        foreach ($permissions as $key1 => $item1) {
            $item1['own'] = $role->hasPermissionTo($item1['id']) ? 'checked' : false;
            if (isset($item1['child'])) {
                foreach ($item1['child'] as $key2 => $item2) {
                    $item2['own'] = $role->hasPermissionTo($item2['id']) ? 'checked' : false;
                    if (isset($item2['child'])) {
                        foreach ($item2['child'] as $key3 => $item3) {
                            $permissions[$key1]['child'][$key2]['child'][$key3]['own'] = $role->hasPermissionTo($item3['id']) ? 'checked' : false;
                            if (isset($item3['child'])) {
                                foreach ($item3['child'] as $key4 => $item4) {
                                    $permissions[$key1]['child'][$key2]['child'][$key3]['child'][$key4]['own'] = $role->hasPermissionTo($item4['id']) ? 'checked' : false;
                                    
                                }
                            }
                        }
                    }
                }
            }
        }
        return view('backend.role.permission', compact('role', 'permissions'));
    }

    /**
     * 存储权限
     */
    public function assignPermission(Request $request, $id)
    {
        $role        = Role::findOrFail($id);
        $permissions = $request->get('permissions');

        if (empty($permissions)) {
            $role->permissions()->detach();
            return response()->json(['code' => 1, 'msg' => '已更新角色权限']);
            return redirect()->to(route('admin.role'))->with(['status' => '已更新角色权限']);
        }
        $role->syncPermissions($permissions);
        return response()->json(['code' => 1, 'msg' => '已更新角色权限']);
    }
    
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'role':
                $query = new Role();
                break;
			case 'hasdel':
                $query = new Role();
				$query = $query->onlyTrashed();
                break;	
            default:
                $query = new Role();
                break;
        }
        $res  = $query->paginate($request->get('limit', 30))->toArray();
        $data = [
            'code'  => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }
}
