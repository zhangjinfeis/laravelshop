<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManagerPower;
use App\Models\ManagerPowerRole;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * 权限控制器
 * @author my 2017-10-25
 * Class PowerController
 * @package App\Http\Controllers\Admin
 */
class ManagerPowerController extends Controller
{

    /**
     * 权限列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        //查询分组
        $sign['groups'] = ManagerPower::select('group')->where('group','<>','')->whereNotNull('group')->groupBy('group')->get();
        if(isset($request->group)){
            $sign['list'] = ManagerPower::where('group',$request->group)->orderBy('group','asc')->paginate(20);
        }else{
            $sign['list'] = ManagerPower::orderBy('group','desc')->paginate(20);
        }
        return view("admin/manager_power/index",$sign);
    }

    /**
     * 创建权限
     * @author my  2017-10-25
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ajaxCreate(Request $request){
        $rule = [
            'name'=>'required|between:1,50|unique:manager_power',
            'description'=>'required|between:1,50',
        ];
        if($request->group){
            $rule['group'] = 'between:1,20';
        }

        $message = [
            'required' => ':attribute不能为空',
            'name.between' => ':attribute字符长度1-50',
            'name.unique' => ':attribute已存在',
            'description.between' => ':attribute字符长度1-50',
            'group.between' => ':attribute字符长度1-20',
        ];
        $replace = [
            'name'=>'权限名称',
            'description'=>'描述',
            'group'=>'标签分组',
        ];

        $validator = Validator::make($request->all(),$rule,$message,$replace);
        if ($validator->fails()){
            return response()->json(['status'=>0,'msg'=>$validator->errors()->first()]);
        }


        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['group'] = $request->group;

        $manager_power = ManagerPower::create($data);
        if($manager_power){
            return response()->json(['status'=>1,'msg'=>'新增成功']);
        }else{
            return response()->json(['status'=>1,'msg'=>'新增失败']);
        }
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $power = ManagerPower::find($request->id);
            $rule = [
                'name'=>['required','between:1,50',Rule::unique('manager_power')->ignore($request->id)],
                'description'=>'required|between:1,50',
            ];
            if($request->group){
                $rule['group'] = 'between:1,20';
            }
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-50',
                'name.unique' => ':attribute已存在',
                'description.between' => ':attribute字符长度1-50',
                'group.between' => ':attribute字符长度1-20',
            ];
            $replace = [
                'name'=>'权限名称',
                'description'=>'描述',
                'group'=>'标签分组',
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

            $power->name = $request->name;
            $power->description = $request->description;
            $power->group = $request->group;
            if($power->save()){
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }else{
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }

        }else{
            $sign['power'] = ManagerPower::find($request->id);
            return view('admin/manager_power/edit',$sign);
        }
    }

    /**
     * 删除权限
     * @author my  2017-10-25
     * @param Request $request 请求
     * @return array
     */
    public function ajaxDel(Request $request){
        DB::beginTransaction();
        $tag = true;
        //验证分类信息是否正确
        $tag = ManagerPower::where('id',$request->id)->delete();

        $a = ManagerPowerRole::where('power_id',$request->id)->delete();
        if(!($a >= 0)){
            $tag = false;
        }
        //执行删除操作
        if($tag){
            DB::commit();
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            DB::rollBack();
            return ['status'=>0,'msg'=>'参数出错'];
        }
    }

}