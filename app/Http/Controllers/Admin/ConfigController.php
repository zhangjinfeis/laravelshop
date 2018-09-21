<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pic;
use App\File;
use App\Models\Config;
use App\Models\ConfigCate;
use Validator;
use Illuminate\Validation\Rule;

//配置管理
class ConfigController extends Controller
{
    private $table = 'config'; //表名

    /**
     * 配置列表
     * @author kevin 2017-11-06
     */
    public function index(Request $request){
        //配置分类
        $cate = ConfigCate::orderBy('sort','asc')->get();
        $sign['cate'] = $cate;
        //当前cate_id
        $sign['cate_id'] = $request->cate_id?$request->cate_id:$cate[0]->id;
        //参数
        $config = Config::where('cate_id',$sign['cate_id'])->orderBy('sort','asc')->get();
        $sign['conf'] = $config;
        return view("admin.config.index",$sign);
    }

    /**
     * 创建
     * @author kevin 2017-11-06
     */
    public function ajaxCreate(Request $request){
        $rule = [
            'name' => 'required|between:1,20',
            'key' => 'required|unique:config,key',
            'sort' => 'required',
        ];
        if($request->width){
            $rule['width'] = 'numeric';
        }
        if($request->height){
            $rule['height'] = 'numeric';
        }
        if($request->size){
            $rule['size'] = 'numeric';
        }
        $message = [
            'required' => ':attribute不能为空',
            'name.between' => ':attribute字符长度1-20',
            'key.unique' => ':attribute已存在',
            'numeric' => ':attribute必须为数字',
        ];
        $replace = [
            'name' => '参数名称',
            'key' => '键',
            'sort' => '排序',
            'width' => '宽度',
            'height' => '高度',
            'size' => '图片允许大小',
        ];
        $validator = Validator::make($request->all(),$rule,$message,$replace);
        if ($validator->fails()){
            $a = $validator->errors()->toArray();

            foreach($a as $k => $v){
                $data['field'] = $k;
                $data['msg'] = $v[0];
                break;
            }
            return response()->json(['status'=>0,'msg'=>$data['msg'],'field'=>$data['field']]);
        }


        $config = new Config();
        $config->cate_id = $request->cate_id;
        $config->name = $request->name;
        $config->type = $request->type;
        $config->key = $request->key;
        $config->value = $request->value;
        if(in_array($request->type,[3,4])){
            $config->width = $request->width;
            $config->height = $request->height;
            $config->size = $request->size;
        }
        if(in_array($request->type,[5])){
            $config->width = $request->width;
            $config->height = $request->height;
            $config->custom = $request->custom;
        }
        $config->tips = $request->tips;
        $config->sort = $request->sort;
        $res = $config->save();
        if(!$res){
            return response()->json(['status'=>0,'msg'=>'新增失败']);
        }else{
            return response()->json(['status'=>1,'msg'=>'新增成功']);
        }
    }

    /**
     * 编辑
     * @author kevin 2017-11-06
     */
    public function edit(Request $request){
        if($request->isMethod('post')){

            $rule = [
                'name' => 'required|between:1,20',
                'key' => [
                    'required',
                    Rule::unique('config')->ignore($request->id)
                ],
                'sort' => 'required',
            ];
            if($request->width){
                $rule['width'] = 'numeric';
            }
            if($request->height){
                $rule['height'] = 'numeric';
            }
            if($request->size){
                $rule['size'] = 'numeric';
            }
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-20',
                'key.unique' => ':attribute已存在',
                'numeric' => ':attribute必须为数字',
            ];
            $replace = [
                'name' => '参数名称',
                'key' => '键',
                'sort' => '排序',
                'width' => '宽度',
                'height' => '高度',
                'size' => '图片允许大小',
            ];
            $validator = Validator::make($request->all(),$rule,$message,$replace);
            if ($validator->fails()){
                $a = $validator->errors()->toArray();
                foreach($a as $k => $v){
                    $data['field'] = $k;
                    $data['msg'] = $v[0];
                    break;
                }
                return response()->json(['status'=>0,'msg'=>$data['msg'],'field'=>$data['field']]);
            }

            $config = Config::find($request->id);
            $config->cate_id = $request->cate_id;
            $config->name = $request->name;
            $config->type = $request->type;
            $config->key = $request->key;
            if(in_array($request->type,[3,4])){
                $config->width = $request->width;
                $config->height = $request->height;
                $config->size = $request->size;
            }
            if(in_array($request->type,[5])){
                $config->width = $request->width;
                $config->height = $request->height;
                $config->custom = $request->custom;
            }
            $config->tips = $request->tips;
            $config->sort = $request->sort;
            $res = $config->save();
            if(!$res){
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }else{
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }
        }else{
            //参数
            $config = Config::find($request->id);
            //系统字段不允许编辑
            if($config->is_system){
                exit('系统字段无法编辑');
            }
            $sign['conf'] = $config;

            //参数分类
            $cate = ConfigCate::orderBy('sort','asc')->get();
            $sign['cate'] = $cate;

            return view('admin.config.edit',$sign);
        }
    }


    /**
     * 编辑参数内容
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxEditValue(Request $request){
        $input = $request->all();
        unset($input['pic_not_use_id']);
        unset($input['pic_use_id']);
        foreach($input as $k => $v){
            Config::where('key',$k)->update(['value'=>$v]);
        }
        return response()->json(['status'=>1,'msg'=>'编辑成功']);
    }


    /**
     * 删除
     * @author kevin 2017-11-06
     */
    public function ajaxDel(Request $request){
        //验证分类是否正确
        $manager_menu = Config::find($request->id);
        //执行删除操作
        if(isset($manager_menu)){//若存在则删除
            $manager_menu->delete();
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status'=>0,'msg'=>'删除失败，未找到参数'];
        }
    }


}