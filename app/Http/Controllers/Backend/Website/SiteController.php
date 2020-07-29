<?php

namespace App\Http\Controllers\Backend\Website;

use App\Models\Backend\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class SiteController extends Controller
{

    /**
     * 站点设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.site.siteconfig');
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

        $data = Website::first();
        return response()->json($data);
    }
    public function update(Request $request)
    {
        $siteconfig = Website::first();
		$data = $request->all();
		if ($siteconfig->update($data)) {
			return response()->json(1);
			//return redirect()->to(route('site.site.index'))->with(['status' => '更新用户成功']);
        }
		return response()->json(2);
		
    }

}
	//	return response()->json($data);
