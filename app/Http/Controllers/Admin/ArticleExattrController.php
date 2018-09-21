<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArticleExattr;
use Validator;
use Illuminate\Validation\Rule;

//配置管理
class ArticleExattrController extends Controller
{

    /**
     * 创建
     * @author kevin 2017-11-06
     */
    public function create(Request $request){
        $rule = [
            'name' => 'required|between:1,20',
            'key' => 'required|unique:article_exattr,key',
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
        $config = new ArticleExattr();
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

            $config = ArticleExattr::find($request->id);
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
            $config = ArticleExattr::find($request->id);
            $sign['page'] = $config;
            return view('admin.article_exattr.edit',$sign);
        }
    }


    /**
     * 删除
     * @author kevin 2017-11-06
     */
    public function ajaxDel(Request $request){
        //验证分类是否正确
        $manager_menu = ArticleExattr::find($request->id);
        //执行删除操作
        if(isset($manager_menu)){//若存在则删除
            $manager_menu->delete();
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status'=>0,'msg'=>'删除失败，未找到参数'];
        }
    }


}