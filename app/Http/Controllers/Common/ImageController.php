<?php
namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pic;
//获取图片验证码
class ImageController extends Controller
{
    /**
     * 查看某张图片
     * @author my  2017-10-26
     * @param Request $request
     * @param Pic $pic
     */
    public function show(Request $request,$pic_md5){
        $pic = Pic::where("md5",$pic_md5)->first();
        if($pic->path){
            header('Content-type: image/jpeg');
            $path = $request->root().$pic->path;
            exit(file_get_contents($path));
        }else {
            exit();
        }
    }
}
