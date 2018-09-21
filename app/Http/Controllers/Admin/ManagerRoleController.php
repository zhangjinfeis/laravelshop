<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManagerMenu;
use Illuminate\Http\Request;
use App\Models\ManagerPower;
use App\Models\ManagerRole;
use App\Models\ManagerPowerRole;
use Validator;
use Illuminate\Support\Facades\DB;

/**
 * 角色控制器
 * @author my 2017-10-25
 * Class RoleController
 * @package App\Http\Controllers\Admin
 */
class ManagerRoleController extends Controller
{
    /**
     * 角色列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sign['list'] = ManagerRole::all();
        return view('admin.manager_role.index',$sign);
    }


    /**
     * 新增角色
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'name' => 'required|between:1,50',
            ];
            if($request->description){
                $rule['description'] ='between:1,20';
            }

            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-50',
                'description.between' => ':attribute字符长度1-20'
            ];
            $replace = [
                'name' => '角色名称',
                'description' => '描述'
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


            //新增角色
            $role['name'] = $request->name;
            if(isset($request->description)){
                $role['description'] = $request->description;
            }
            $role = ManagerRole::create($role);
            if(!$role){
                return response()->json(['status'=>0,'msg'=>'新增失败']);
            }
            //当有权限时，进行新增
            if(isset($request->power)){
                $role->powers()->attach($request->power);
            }
            return response()->json(['status'=>1,'msg'=>'新增成功']);

        }else{

            //载入权限（按分组）
            $groups = ManagerPower::select('group')->groupBy('group')->get()->toArray();
            $power = ManagerPower::orderBy('group','asc')->get()->toArray();
            foreach($groups as $k => $v){
                foreach($power as $key => $val){
                    if($v['group'] == $val['group']){
                        $groups[$k]['child'][] = $val;
                    }
                }
            }
            $sign['power'] = $groups;

            return view('admin.manager_role.create',$sign);
        }
    }


    /**
     * 编辑角色
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        if($request->isMethod('post')){

            $rule = [
                'name' => 'required|between:1,50',
            ];
            if($request->description){
                $rule['description'] ='between:1,20';
            }

            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-50',
                'description.between' => ':attribute字符长度1-20'
            ];
            $replace = [
                'name' => '角色名称',
                'description' => '描述'
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
            //更新角色
            $role = ManagerRole::find($request->id);
            $role->name = $request->name;
            if(isset($request->description)){
                $role->description = $request->description;
            }
            $added = $role->save();
            if(!$added){
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            };
            //更新权限
            $role->powers()->sync($request->power);
            return response()->json(['status'=>1,'msg'=>'编辑成功']);

        }else{
            //载入角色
            $sign['role'] = ManagerRole::find($request->id);

            //载入当前角色权限
            $role = new ManagerRole();
            $sign['power'] = $role->powerGroupList($request->id);

            return view('admin.manager_role.edit',$sign);
        }
    }

    /**
     * 删除角色
     * @author my  2017-10-25
     * @param Request $request 请求
     * @return array
     */
    public function ajaxDel(Request $request){
        $role = ManagerRole::find($request->id);
        if(!$role){
            return response()->json(["status" => 0,"msg" => "参数错误"]);
        }
        $role->powers()->detach();
        $role->users()->detach();
        $role->delete();
        return response()->json(["status" => 1,"msg" => "删除成功"]);

/*        DB::beginTransaction();
        $tag = true;
        //验证分类信息是否正确
        $tag = ManagerRole::where('id',$request->id)->delete();
        $a = ManagerPowerRole::where('role_id',$request->id)->delete();
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
        }*/
    }


    /**
     * 显示一个角色的权限
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_page_powers(Request $request){
        $role = new ManagerRole();
        $sign['power'] = $role->powerGroupList($request->id);
        $res = response()->view('admin.manager_role.powers',$sign)->getContent();
        if($res){
            return response()->json(['status'=>1,'msg'=>'获取成功','body'=>$res]);
        }else{
            return response()->json(['status'=>0,'msg'=>'获取失败']);
        }

    }

}
