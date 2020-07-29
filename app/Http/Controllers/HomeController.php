<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $url = "http://www.baidu.com/link?url=nS2MGJqjJ4zBBpC8yDF8xDh8vibi1lVeE7gGr9UONBu";

$info = parse_url($url);
$fp = fsockopen($info['host'], 80,$errno, $errstr, 30);
fputs($fp,"GET {$info['path']}?{$info['query']} HTTP/1.1\r\n");
fputs($fp, "Host: {$info['host']}\r\n");
fputs($fp, "Connection: close\r\n\r\n");
$rewrite = '';
while(!feof($fp)) {
    $line = fgets($fp);
    if($line != "\r\n" ) {
        if(strpos($line,'Location:') !== false) {
            $rewrite = str_replace(array("\r","\n","Location: "),'',$line);
        }
    }else {
        break;
    }
}
var_dump($rewrite); //结果显示：string(22) "http://www.google.com/" 
        return response()->json($rewrite);
        //return view('home');
    }
}
