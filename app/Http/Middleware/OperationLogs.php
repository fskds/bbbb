<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use App\Models\Backend\OperationLog;
use App\Models\Backend\Admin\Permission;

/**
 * 全局的用户操作日志中间件
 */
class OperationLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
public function handle($request, Closure $next)
    {
        $user_id = 0;
        $user_name = '';
        if (Auth::check()) {
            $user_id = (int)Auth::id();
            $user_name = Auth::user()->name;
        }

        if ('GET' != $request->method()) {
            $router_as = $request->route()->action['as'];
            if (empty($router_as)) {
                return $next($request);
            }
            $permisson = new Permission();
            $attributesArr = $permisson->getAllCacheAttributes();
            foreach ($attributesArr as $key => $item) {
                unset($attributesArr[$key]['deleted_at']);
            }

            $allName       = $this->getAllName($attributesArr, $router_as);
            $operate_name  = $allName['operateName'];
            if (empty($operate_name)) {
                return $next($request);
            }
            $sub_menu_name = $allName['subMenuName'];
            $menu_name     = $allName['menuName'];

            // record operate log
            $input = $request->all();
            $log = new OperationLog();
            $log->user_id       = $user_id;
            $log->user_name     = $user_name;
            $log->menu_name     = $menu_name;
            $log->sub_menu_name = $sub_menu_name;
            $log->operate_name  = $operate_name;
            $log->path          = $request->path();
            $log->method        = $request->method();
            $log->ip            = $request->ip();
            $log->input         = $this->formatInput($input);
            try {
                $log->save();
            } catch (\Exception $exception) {
                // pass
            }

        }
        return $next($request);
    }

    private function getAllName(array $operateArr, string $needle): array
    {
        $return = [
            'menuName' => '',
            'subMenuName'    => '',
            'operateName' => '',
        ];

        $pid = 0;
        foreach ($operateArr as $op) {
            if (array_key_exists($needle, array_flip($op))) {
               $pid = $op['pid'];
               $return['operateName'] = $op['display_name'];
               break;
            }
        }

        if ($pid) {
            foreach ($operateArr as $op) {
                if ($op['id'] == $pid) {
                    $pid = $op['pid'];
                    $return['subMenuName'] = $op['display_name'];
                    break;
                }
            }
        }
        if ($pid) {
            foreach ($operateArr as $op) {
                if ($op['id'] == $pid) {
                    $return['menuName'] = $op['display_name'];
                    break;
                }
            }
        }

        return $return;
    }

    private function formatInput(array $input)
    {
        $tmp = [];
        foreach ($input as $key => $in) {
            if (\substr($key, 0, 1) != '_') {
                    $tmp[$key] = $in;
            }
        }

        return json_encode($tmp, JSON_UNESCAPED_UNICODE);
    }

}