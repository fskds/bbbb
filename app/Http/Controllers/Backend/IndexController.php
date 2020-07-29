<?php

namespace App\Http\Controllers\Backend;



use App\Models\Backend\Menu;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Route;
use App\Models\Backend\Admin\Role;
use App\Models\Backend\Admin\Admin;
use App\Models\Backend\Admin\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 后台布局
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function layout()
    {
        return view('backend.layout');
    }

	// 获取初始化数据
    public function getSystemInit(){
        $homeInfo = [
            'title' => '首页',
            'href'  => 'welcome-3.html',
        ];
        $logoInfo = [
            'title' => 'LAYUI MINI',
            'image' => 'images/logo.png',
			'href' => ''
        ];
        $menuInfo = $this->getMenuList();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        return response()->json($systemInit);
    }
	// 获取菜单列表
    private function getMenuList(){
        $menuList = Permission::select(['id','pid','display_name','route','icon','status'])->where('pid', 0)->orderBy('sort', 'asc')->orderBy('id', 'asc')
                ->with(['child' => function($query){
                    $query->select(['id','pid','display_name','route','icon','status'])->orderBy('sort', 'asc')->orderBy('id', 'asc')
                        ->with(['child' => function($query){
                            $query->select(['id','pid','display_name','route','icon','status'])->orderBy('sort', 'asc')->orderBy('id', 'asc')
                                ->with(['child' => function($query){
                                    $query->select(['id','pid','display_name','route','icon','status'])->orderBy('sort', 'asc')->orderBy('id', 'asc');
                                }]);
                        }]);
                }])->orderBy('sort', 'desc')->get();

        foreach ($menuList as $key1 => $item1) {
            if(Auth::guard('admin')->user()->hasDirectPermission($item1['id']) && $item1['status']==1){
                $item1['title'] = $item1['display_name'];
                if ($item1['route']){
                    $item1['route'] = route($item1['route']);
                }
                $item1['href'] = $item1['route'];
                unset($item1['route']);
                unset($item1['display_name']);
                if (isset($item1['child'])) {
                    foreach ($item1['child'] as $key2 => $item2) {
                        if(Auth::guard('admin')->user()->hasDirectPermission($item2['id']) && $item2['status']==1){
                            $item2['title'] = $item2['display_name'];
                            if ($item2['route']){
                                $item2['route'] = route($item2['route']);
                            }
                            $item2['href'] = $item2['route'];
                            unset($item2['route']);
                            unset($item2['display_name']);
                            if (isset($item2['child'])) {
                                foreach ($item2['child'] as $key3 => $item3) {
                                    if(Auth::guard('admin')->user()->hasDirectPermission($item3['id']) && $item3['status']==1){
                                        $item3['title'] = $item3['display_name'];
                                        if ($item3['route']){
                                            $item3['route'] = route($item3['route']);
                                        }
                                        $item3['href'] = $item3['route'];
                                        unset($item3['route']);
                                        unset($item3['display_name']);
                                        if (isset($item3['child'])) {
                                            foreach ($item3['child'] as $key4 => $item4) {
                                                if(Auth::guard('admin')->user()->hasDirectPermission($item4['id']) && $item4['status']==1){
                                                    $item4['title'] = $item4['display_name'];
                                                    if ($item4['route']){
                                                        $item4['route'] = route($item4['route']);
                                                    }
                                                    $item4['href'] = $item4['route'];
                                                    unset($item4['route']);
                                                    unset($item4['display_name']);
                                                }else{
                                                    unset($item3['child'][$key4]);
                                                }
                                            }
                                        }  
                                    }else{
                                        unset($item2['child'][$key3]);
                                    }
                                }
                            }
                        }else{
                            unset($item1['child'][$key2]);
                        }        
                    }
                }
            }else{
                unset($menuList[$key1]);
            }
        }	
        return $menuList;
    }
    /**
     * 后台首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.index.index');
    }

}
