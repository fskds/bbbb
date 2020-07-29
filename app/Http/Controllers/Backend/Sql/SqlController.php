<?php

namespace App\Http\Controllers\Backend\Sql;

use Illuminate\Support\Facades\Storage;
use Ifsnop\Mysqldump as IMysqldump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class SqlController extends Controller
{
    protected $directory = 'backup';
    
    public function index()
    {
        return view('backend.backup');
    }
    public function data()
    {
        $disk = Storage::disk('backup');
        // 获取目录下的文件
        $directory = '/';
        //return response()->json($this->directory);
        $files = $disk->files();
        
        $backup = [];
        foreach ($files as $k => $v) {
            if (substr($v,strpos($v,'.')+1) === 'sql') {
                $backup[$k]['filename'] = substr($v,strpos($v,'/'));
                $backup[$k]['filesize'] = $this->count_size($disk->size($v));            //文件大小
                $backup[$k]['time'] = date('Y-m-d H:i:s', $disk->lastModified($v)); //最后修改时间
            }
        }
        $data = [
            'code'  => 0,
            'msg'   => '正在请求中...',
            'count' => count($backup),
            'data'  => $backup
        ];
        return response()->json($data);
    }

    public function store()
    {
        //备份数据库配置
        $dumpSettings = array(
            'compress' => IMysqldump\Mysqldump::NONE,
            'no-data' => false,
            'add-drop-table' => true,
            'single-transaction' => true,
            'lock-tables' => true,
            'add-locks' => true,
            'extended-insert' => true,
            'disable-foreign-keys-check' => true,
            'skip-triggers' => false,
            'add-drop-trigger' => true,
            'databases' => true,
            'add-drop-database' => true,
            'hex-blob' => true
        );
        try {
            $dump = new IMysqldump\Mysqldump(
                'mysql:host=' . config('database.connections.mysql.host') . ';dbname=' . config('database.connections.mysql.database'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                $dumpSettings
            );
            $prefix = 'sitename-backup';
            $filename = date('Y') . '-' . date('m') . '-' . date('d') . '-' . date('H') . '-' . date('i') . '-' . date('s');
            $name = $prefix . $filename . ".sql";
            $dump->start(storage_path() . "/app/backup/" . $name);
            return response()->json(['code' => 1, 'msg' => '成功备份数据']);
        } catch (\Exception $e) {
            return response()->json(['code' => 0, 'msg' => $e->getMessage()]);
        }

    }
    
    public function recover(Request $request)
    {
        $disk = Storage::disk('backup');
        $filename = $request->get('filename');
        $exists = $disk->exists($filename);
        if (!$exists) {
            return response()->json(['code' => 0, 'msg' => 'sql文件不存在']);
        }
        
        //导入sql文件操作
        $sql = file_get_contents(storage_path()."/app/backup/".$filename);
        $result = DB::unprepared($sql);
        
        if ($result != 1) {
            return response()->json(['code' => 0, 'msg' => '数据库恢复失败']);
        }
        return response()->json(['code' => 1, 'msg' => '数据库恢复成功']);
    }
    //删除
    public function destroy(Request $request)
    {
        
        $disk = Storage::disk('backup');
        $filename = $request->get('filename');
        $exists = $disk->exists($filename);
        if (!$exists) {
            return response()->json(['code' => 0, 'msg' => '文件不存在']);
        }
        // 删除单条文件
        $result = $disk->delete($filename);
        if($result){
            return response()->json(['code' => 1, 'msg' => '数据成功删除']);
        }else{
            return response()->json(['code' => 0, 'msg' => '删除失败！']);
        }
    }
    public function download(Request $request)
    {
        $disk = Storage::disk('backup');
        $filename = $request->get('filename');
        $exists = $disk->exists($filename);
        if (!$exists) {
            return response()->json(['code' => 0, 'msg' => '文件不存在']);
        }
        //完整路径下载
        //
        return response()->download(storage_path().'/app/'.$this->directory.'/'.$filename);
        //return response()->json(['code' => 0, 'msg' => '开始下载']);
    }
    
    //单位换算
    public function count_size($bit)
    {
        $type = array('Bytes','KB','MB','GB','TB');
        for($i = 0; $bit >= 1024; $i++)
        {
            $bit/=1024;
        }
        return (floor($bit*100)/100).$type[$i];
    }
}
