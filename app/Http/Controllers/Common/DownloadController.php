<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Session;
//获取图片验证码
class DownloadController extends Controller
{

    /**
     * 下载文件
     * @author kevin 2017-11-02
     */
    public function downloadFile(){
        $md5 = request('md5');
        $info = File::where(['md5'=>$md5])->first();
        if(!$info) return back()->withErrors(['message' => '文件不存在']);
        $file_path = '.'.$info->url;
        if(!is_file($file_path)) return back()->withErrors(['message' => '文件不存在']);
        $fp = fopen($file_path,"r");
        $file_size = $info->size;
        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:".$file_size);
        Header("Content-Disposition: attachment; filename=".$info->name);
        $buffer = 1024;
        $file_count = 0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count<$file_size){
            $file_con = fread($fp,$buffer);
            $file_count += $buffer;
            echo $file_con;
        }
        fclose($fp);
    }
}
