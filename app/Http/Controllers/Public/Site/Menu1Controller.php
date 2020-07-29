<?php

namespace App\Http\Controllers\Site;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\MenuCreateRequest;
use App\Http\Requests\MenuUpdateRequest;
class MenuController extends Controller
{

    /**
     * 站点设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('site.menu.index');
    }
	public function create()
    {
        $menus = $this->tree();
        return view('site.menu.create', compact('menus'));
    }

	public function store(MenuCreateRequest $request)
    {
        $data = $request->all();
		if(empty($data['sort'])){
			$data['sort'] = 0;
		}

        if (Menu::create($data)) {
            return redirect()->to(route('site.menu'))->with(['status' => '添加成功']);
        }
        return redirect()->to(route('site.menu'))->withErrors('系统错误');
    }
	
	public function edit($id)
    {
        $menu  = Menu::findOrFail($id);
        $menus = $this->tree();
        return view('site.menu.edit', compact('menu', 'menus'));
    }
	
	public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        //$data       = array_filter($request->all());
		$data       = $request->all();
        if ($menu->update($data)) {
            return redirect()->to(route('site.menu'))->with(['status' => '更新菜单成功']);
        }
        return redirect()->to(route('site.menu'))->withErrors('系统错误');
		
    }
	
	public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }
        $Menu = Menu::find($ids[0]);
        if (!$Menu) {
            return response()->json(['code' => -1, 'msg' => '菜单不存在']);
        }
        // 如果有子权限，则禁止删除
        if (Menu::where('parent_id', $ids[0])->first()) {
            return response()->json(['code' => 2, 'msg' => '存在子菜单禁止删除']);
        }
		if ($Menu->delete()) {
			return response()->json(['code' => 0, 'msg' => '删除成功']);
		}
        return response()->json(['code' => 1, 'msg' => '删除失败']);
    }
    /**
     * 数据表格接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function data(Request $request)
    {
		$query = new Menu();
		$query = $query->where('parent_id', $request->get('parent_id', 0));
		$res  = $query->paginate(20)->toArray();
		$data = [
            'code'  => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }
	


	public function tree($list = [], $pk = 'id', $pid = 'parent_id', $child = '_child', $root = 0)
    {
        if (empty($list)) {
            $list = Menu::get()->toArray();
        }
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
	//	return response()->json($data);
