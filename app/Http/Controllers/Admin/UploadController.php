<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pic;
use App\Models\File;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

/**
 * 文件上传与下载管理
 * @author my 2017-10-25
 * Class AreaController
 * @package App\Http\Controllers\Admin
 */
class UploadController extends Controller
{
    private $config; //基本配置

    public function __construct()
    {
        $this->config = [
            //'need_original_name_as_path'    => false,
            'allowed_ext_pic'               => ['png', 'jpg', 'jpeg', 'gif', 'bmp'],
            'allowed_ext_file'              => [
                'png', 'jpg', 'jpeg', 'gif', 'bmp',
                'flv', 'swf', 'mkv', 'avi', 'rm', 'rmvb', 'mpeg', 'mpg',
                'ogg', 'ogv', 'mov', 'wmv', 'mp4', 'webm', 'mp3', 'wav', 'mid',
                'rar', 'zip', 'tar', 'gz', '7z', 'bz2', 'cab', 'iso', 
                'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'md', 'xml'
            ],
            'max_allowed_size_pic'  => 0, // 0-表示不限制 单位Mb
            'max_allowed_size_file' => 0,
            //'upload_root_path'      => storage_path() . '/storage/uploads/',
        ];
    }


    /**
     * 上传图片
     * @author my  2017-10-26
     * @param Request $request
     * @return array
     */
    public function ajaxUploadImg(Request $request){
        //获取文件
        $image = $request->file('file');
        //校验文件
        if($image->getError() > 0){//文件传输有错误
            switch ($image->getError()){
                case 1:
                    return ["status"=>0,"msg"=>"图片上传失败，图片大小不能超出".ini_get('upload_max_filesize')];
                    break;
                case 2:
                    return ["status"=>0,"msg"=>"图片上传失败，图片大小不能超出表单的提交限制"];
                    break;
                case 3:
                    return ["status"=>0,"msg"=>"图片上传失败，请检查网络状态是否可用"];
                    break;
                case 4:
                    return ["status"=>0,"msg"=>"图片上传失败，请检查图片文件完整性"];
                    break;

            }
        }else if(!in_array($image->extension(),$this->config['allowed_ext_pic'])){
            //判断文件类型
            return ["status"=>0,"msg"=>"上传图片失败，请上传正确的图片格式文件"];
        }

        //存储文件
        $filename = md5(time().str_random(40)).".".$image->clientExtension();//新文件名
        $filepath = date("Y")."/".date("m")."/".date("d");
        $path = $image->storeAs('public'.'/'.$filepath,$filename);  //起始路径为storage/app

        /**
         * 压缩图片
         */
        $manager  = new ImageManager();
        $image_new = $manager ->make('../storage/app/public/'.$filepath.'/'.$filename)->orientate();
        if($request->width && $request->height){
            $image_new = $image_new->fit($request->width,$request->height);
        }elseif($request->width){
            $image_new = $image_new->widen($request->width,function($constraint){
                $constraint->upsize();
            });
        }elseif($request->height){
            $image_new = $image_new->heighten($request->height,function($constraint){
                $constraint->upsize();
            });
        }
        $image_new->save('../storage/app/public/'.$filepath.'/'.$filename);


        //修改数据库记录
        $param['original_name'] = $image->getClientOriginalName();
        $param['name'] = $filename;
        $param['path'] = "/storage/".$filepath.'/'.$filename;
        $param['md5'] = md5($param['path'].env("md5_key",""));
        $param['sha1'] = md5($param['path'].env("sha1_key",""));
        $param['url'] = '/image/'.$param['md5'];
        //$imageInfo = getimagesize($request->root().$param['path']);
        $param['width'] = $image_new->width();
        $param['height'] = $image_new->height();
        $param['size'] = $image_new->filesize();//获得文件的大小（字节）
        $param['status'] = 0;
        $pic = Pic::create($param);//创建一条图片记录
        //返回信息
        return ["status"=>1,"msg"=>"","data"=>$pic];
    }


    /**
     * ckeditor上传图片
     * @author my  2017-11-3
     * @param Request $request
     * @return array
     */
    public function ajaxCkeditorImg(Request $request){
        $image = $request->file('upload');
        $callback = $_REQUEST["CKEditorFuncNum"];
        $error = "";
        //校验文件
        if($image->getError() > 0){//文件传输有错误
            switch ($image->getError()){
                case 1:
                    $error = "图片上传失败，图片大小不能超出".ini_get('upload_max_filesize');
                    break;
                case 2:
                    $error = "图片上传失败，图片大小不能超出表单的提交限制";

                    break;
                case 3:
                    $error = "图片上传失败，请检查网络状态是否可用";
                    break;
                case 4:
                    $error = "图片上传失败，请检查图片文件完整性";
                    break;
            }
            if(!empty($error)){
                return "<script>window.parent.CKEDITOR.tools.callFunction($callback, '', '$error');</script>";
            }

        }else if(!in_array($image->extension(),$this->config['allowed_ext_pic'])){
            //判断文件类型
            $error = "上传图片失败，请上传正确的图片格式文件";
            return "<script>window.parent.CKEDITOR.tools.callFunction($callback, '', '$error');</script>";
        }



        //存储文件
        $filename = md5(time().str_random(40)).".".$image->clientExtension();//新文件名
        $filepath = date("Y")."/".date("m")."/".date("d");
        $path = $image->storeAs('public'.'/'.$filepath,$filename);  //起始路径为storage/app

        /**
         * 压缩图片
         */
        $manager  = new ImageManager();
        $image_new = $manager ->make('../storage/app/public/'.$filepath.'/'.$filename);
        $width_new = $image_new->width();
        $height_new = $image_new->height();
        if(0 < $request->max_width && $request->max_width < $width_new){
            $resize_width = $request->max_width;
            $resize_height = floor($request->max_width*$height_new/$width_new);
            $image_new = $image_new->resize($resize_width, $resize_height)->save('../storage/app/public/'.$filepath.'/'.$filename);
        }elseif(0 < $request->max_height && $request->max_height < $height_new){
            $resize_height = $request->max_height;
            $resize_width = floor($request->max_height*$width_new/$height_new);
            $image_new = $image_new->resize($resize_width, $resize_height)->save('../storage/app/public/'.$filepath.'/'.$filename);
        }else{
            $resize_width = $width_new;
            $resize_height = $height_new;
        }

        //修改数据库记录
        $param['original_name'] = $image->getClientOriginalName();
        $param['name'] = $filename;
        $param['path'] = "/storage/".$filepath.'/'.$filename;
        $param['md5'] = md5($param['path'].env("md5_key"));
        $param['sha1'] = md5($param['path'].env("sha1_key"));
        $param['url'] = '/image/'.$param['md5'];
        //$imageInfo = getimagesize($request->root().$param['path']);
        $param['width'] = $resize_width;
        $param['height'] = $resize_height;
        $param['size'] = $image_new->filesize();//获得文件的大小（字节）
        $param['status'] = 0;
        $pic = Pic::create($param);//创建一条图片记录
        //返回信息

        $previewname = $param['url'];
        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".$previewname."','');</script>";
    }

    /**
     * 上传单文件
     * @author kevin  2017-11-01
     */
    public function ajaxUploadFile(Request $request){
        //接收文件
        $file = $request->file('file');
        //验证文件是否存在
        if(!$file) return ['status'=>0,'msg'=>'非法提交'];

        //验证文件后缀是否合法
        $suffix = explode(".",$file->getClientOriginalName());//将原文件名用.分隔
        $suffix = $suffix[1];//取后缀
        if(!in_array(strtolower($suffix), $this->config['allowed_ext_file'])) {
            return ['status'=>0, 'msg'=>'不合法的文件后缀，请重试'];
        }
        //判断大小
        $size = $file->getClientSize();
        $max_size = (int)ini_get('upload_max_filesize')*1024*1024;
        if($size > $max_size) return ['status'=>0,'msg'=>'文件大小超出限制'];

        //生成文件名
        $filename = uniqid().strtolower(str_random(7)).'.'.$suffix;
        //存储文件
        $path = $file->storePubliclyAs(date("Y")."/".date("m")."/".date("d"),$filename);
        //判断文件是否存储成功
        if(!is_file('storage/'.$path)) return ['status'=>0,'msg'=>'文件保存失败,请检查目录权限'];
        //整理数据
        $md5 = md5('storage/'.$path);
        $prename = $file->getClientOriginalName();
        $param = [
            'prename' => $prename,
            'name' => $filename,
            'path' => '/storage/app/public/'.$path,
            'url' => '/storage/'.$path,
            'size' => $size,
            'ext' => $suffix,
            'md5' => $md5,
            'sha1' => sha1('storage/'.$path),
            'status' => 0
        ];
        $res = File::create($param);
        if($res){
            $data = [
                'md5' => $md5,
                'suffix' => $suffix,
                'icon' => config('config.file_suffix')[$suffix],
                'filename' => $prename,
                'path' => '/storage/app/public/'.$path,
            ];
            return ['status'=>1,'msg'=>'上传成功','data'=>$data];
        }else{
            return ['status'=>0,'msg'=>'上传失败'];
        }
    }



}