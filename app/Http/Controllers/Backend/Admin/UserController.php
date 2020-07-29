<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Backend\Admin\Admin;
use App\Models\Backend\Admin\Role;
use App\Models\Backend\Admin\Permission;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminCreateRequest;
use App\Http\Requests\AdminUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCreateRequest $request)
    {
        $data             = $request->all();
        $data['uuid']     = Uuid::uuid();
        $data['password'] = bcrypt($data['password']);
        if (Admin::create($data)) {
			return response()->json(['code' => 1, 'msg' => '成功注册管理员']);
        }
		return response()->json(['code' => 2, 'msg' => '未知失败联系管理员']);
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
        $user = Admin::findOrFail($id);
        return view('Backend.admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUpdateRequest $request, $id)
    {
        $user = Admin::findOrFail($id);

        $data = $request->except('password');

        if ($request->get('password')) {
            $data['password'] = bcrypt($request->get('password'));
        }
        if ($user->update($data)) {
            if ($request->get('password') && Auth::user()->id == $id) {
				return response()->json(['code' => 2, 'msg' => '成功更新管理员']);
            }
			return response()->json(['code' => 1, 'msg' => '成功更新管理员']);
        }
		return response()->json(['code' => 500, 'msg' => '未知失败联系管理员']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function personalEdit($id)
    {
        $user = Admin::findOrFail($id);
        return view('Backend.admin.personalInfo', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function personalUpdate(AdminUpdateRequest $request, $id)
    {
        $user = Admin::findOrFail($id);

        $data = $request->except('password');
        if ($request->get('password')) {
            $data['password'] = bcrypt($request->get('password'));
        }
        if ($user->update($data)) {
            if ($request->get('password') && Auth::user()->id == $id) {
                return redirect()->to(route('admin.logout'));
            }
            return redirect()->to(route('admin.index'))->with(['status' => '更新用户成功']);
        }
        return redirect()->to(route('admin.index'))->withErrors('系统错误');
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
		if(in_array(Auth::user()->id,$ids) || in_array(1,$ids)){
			return response()->json(['code' => 0, 'msg' => '不可删除自己']);
		}else{
			if (Admin::destroy($ids)) {
				return response()->json(['code' => 1, 'msg' => '删除成功']);
			}
		}
        return response()->json(['code' => 0, 'msg' => '删除失败']);
    }
	public function restore(Request $request)
    {
        $ids = $request->get('ids');
		$admin = Admin::withTrashed()->find($ids);
		
		if ($admin->restore()){
			return response()->json(['code' => 1, 'msg' => '恢复用户成功']);
		}
        return response()->json(['code' => 0, 'msg' => '恢复用户失败']);
    }
    /**
     * 分配角色
     */
    public function role(Request $request, $id)
    {
        $user     = Admin::findOrFail($id);
        $roles    = Role::get();
//        $hasRoles = $user->roles;
        foreach ($roles as $role) {
            $role->own = $user->hasRole($role) ? true : false;
        }
//		return response()->json($roles);
        return view('backend.admin.role', compact('roles', 'user'));
    }

    /**
     * 更新分配角色
     */
    public function assignRole(Request $request, $id)
    {
        $user  = Admin::findOrFail($id);
        $roles = $request->get('roles', []);
        if ($user->syncRoles($roles)) {
			return response()->json(['code' => 1, 'msg' => '更新角色成功']);
        }
        return response()->json(['code' => 2, 'msg' => '更新角色失败']);
    }

    /**
     * 分配权限
     */
    public function permission(Request $request, $id)
    {
        $user        = Admin::findOrFail($id);
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
            $item1['own'] = $user->hasDirectPermission($item1['id']) ? 'checked' : false;
            if (isset($item1['child'])) {
                foreach ($item1['child'] as $key2 => $item2) {
                    $item2['own'] = $user->hasDirectPermission($item2['id']) ? 'checked' : false;
                    if (isset($item2['child'])) {
                        foreach ($item2['child'] as $key3 => $item3) {
                            $permissions[$key1]['child'][$key2]['child'][$key3]['own'] = $user->hasDirectPermission($item3['id']) ? 'checked' : false;
                            if (isset($item3['child'])) {
                                foreach ($item3['child'] as $key4 => $item4) {
                                    $permissions[$key1]['child'][$key2]['child'][$key3]['child'][$key4]['own'] = $user->hasDirectPermission($item4['id']) ? 'checked' : false;
                                    
                                }
                            }
                        }
                    }
                }
            }
        }
  
        return view('backend.admin.permission', compact('user', 'permissions'));
    }

    /**
     * 存储权限
     */
    public function assignPermission(Request $request, $id)
    {
        $user        = Admin::findOrFail($id);
        $permissions = $request->get('permissions');

        if (empty($permissions)) {
            $user->permissions()->detach();
			return response()->json(['code' => 1, 'msg' => '已更新用户直接权限']);
        }
        $user->syncPermissions($permissions);
		return response()->json(['code' => 1, 'msg' => '已更新用户直接权限']);
    }
    
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'admin':
                $query = new Admin();
                break;
			case 'hasdel':
                $query = new Admin();
				$query = $query->onlyTrashed();
                break;
			case 'search':
                $query = new Admin();
                $query = $query->where('name','like', '%'.$request->get('name').'%');
                break;	
            default:
                $query = new Admin();
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
