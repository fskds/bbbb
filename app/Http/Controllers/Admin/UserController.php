<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $data             = $request->all();
        $data['uuid']     = \Faker\Provider\Uuid::uuid();
        $data['password'] = bcrypt($data['password']);
        if (Admin::create($data)) {
            return redirect()->to(route('admin.admin'))->with(['status' => '添加用户成功']);
        }
        return redirect()->to(route('admin.admin'))->withErrors('系统错误');
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
        return view('admin.admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
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
            return redirect()->to(route('admin.admin'))->with(['status' => '更新用户成功']);
        }
        return redirect()->to(route('admin.admin'))->withErrors('系统错误');
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
        return view('admin.admin.personalInfo', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function personalUpdate(UserUpdateRequest $request, $id)
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
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }
        if (Admin::destroy($ids)) {
            return response()->json(['code' => 0, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 1, 'msg' => '删除失败']);
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
        return view('admin.admin.role', compact('roles', 'user'));
    }

    /**
     * 更新分配角色
     */
    public function assignRole(Request $request, $id)
    {
        $user  = Admin::findOrFail($id);
        $roles = $request->get('roles', []);
        if ($user->syncRoles($roles)) {
            return redirect()->to(route('admin.admin'))->with(['status' => '更新用户角色成功']);
        }
        return redirect()->to(route('admin.admin'))->withErrors('系统错误');
    }

    /**
     * 分配权限
     */
    public function permission(Request $request, $id)
    {
        $user        = Admin::findOrFail($id);
        $permissions = $this->tree();
        foreach ($permissions as $key1 => $item1) {
            $permissions[$key1]['own'] = $user->hasDirectPermission($item1['id']) ? 'checked' : false;
            if (isset($item1['_child'])) {
                foreach ($item1['_child'] as $key2 => $item2) {
                    $permissions[$key1]['_child'][$key2]['own'] = $user->hasDirectPermission($item2['id']) ? 'checked' : false;
                    if (isset($item2['_child'])) {
                        foreach ($item2['_child'] as $key3 => $item3) {
                            $permissions[$key1]['_child'][$key2]['_child'][$key3]['own'] = $user->hasDirectPermission($item3['id']) ? 'checked' : false;
                        }
                    }
                }
            }
        }
        return view('admin.admin.permission', compact('user', 'permissions'));
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
            return redirect()->to(route('admin.admin'))->with(['status' => '已更新用户直接权限']);
        }
        $user->syncPermissions($permissions);
        return redirect()->to(route('admin.admin'))->with(['status' => '已更新用户直接权限']);
    }
}