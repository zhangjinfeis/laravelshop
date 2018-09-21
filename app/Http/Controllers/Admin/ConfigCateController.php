<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConfigCate;
use Validator;


//配置分类管理
class ConfigCateController extends Controller
{
    /**
     * 配置分类列表
     * @author kevin 2017-11-06
     */
    public function index(){
        $data['list'] = ConfigCate::orderBy('sort','asc')->orderBy('id','asc')->get();
        return view("admin.config_cate.index",$data);
    }

    /**
     * 创建分类
     * @author kevin 2017-11-06
     */
    public function ajaxCreate(Request $request){
        $rule = [
            'name' => 'required|between:1,20',
            'sort' => 'required',
        ];
        $message = [
            'required' => ':attribute不能为空',
            'name.between' => ':attribute字符长度1-20',
        ];
        $replace = [
            'name' => '分类名称',
            'sort' => '排序',
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


        $cate = new ConfigCate();
        $cate->name = $request->name;
        $cate->sort = $request->sort;
        $res = $cate->save();
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
                'sort' => 'required',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-20',
            ];
            $replace = [
                'name' => '分类名称',
                'sort' => '排序',
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


            $cate = ConfigCate::find($request->id);
            $cate->name = $request->name;
            $cate->sort = $request->sort;
            $res = $cate->save();
            if(!$res){
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }else{
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }
        }else{
            $sign['cate'] = ConfigCate::find($request->id);
            return view("admin.config_cate.edit",$sign);
        }
    }

    /**
     * 删除
     * @author kevin 2017-11-06
     */
    public function ajaxDel(Request $request){
        //验证分类是否正确
        $manager_menu = ConfigCate::find($request->id);
        //执行删除操作
        if(isset($manager_menu)){//若存在则删除
            $manager_menu->delete();
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status'=>0,'msg'=>'删除失败，未找到分类'];
        }
    }
}