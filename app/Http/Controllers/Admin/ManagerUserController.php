<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManagerUser;
use App\Models\ManagerRole;
use App\Models\ManagerRoleUser;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * 后台用户控制器
 * @author my 2017-10-25
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class ManagerUserController extends Controller
{
    /**
     * 用户列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = ManagerUser::all();
        $sign['list'] = $list;
        return view('/admin/manager_user/index',$sign);
    }


    /**
     * 创建用户
     * @author my  2017-10-25
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        if($request->isMethod('post')){
            $rule = [
                'name' => 'required|between:1,50',
                'account' => 'required|between:6,16',
                'password' => 'required|between:6,16',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-50',
                'account.between' => ':attribute字符长度6-16',
                'password.between' => ':attribute字符长度6-16',
            ];
            $replace = [
                'name' => '管理员称呼',
                'account' => '账号',
                'password' => '密码',
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

            $manager_user = ManagerUser::create(['name'=>$request->name,'account'=>$request->account,'password'=>bcrypt($request->password)]);
            if(!$manager_user){
                return response()->json(['status'=>0,'msg'=>'新增失败']);
            }
            $manager_user->roles()->attach($request->role_ids);
            return response()->json(['status'=>1,'msg'=>'新增成功']);
        }else{
            //载入角色
            $roles = ManagerRole::all();
            $sign['roles'] = $roles;
            return view('admin.manager_user.create',$sign);
        }
    }

    /**
     * 编辑用户
     * @author my  2017-11-6
     * @param Request $request
     * @param ManagerUser $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'name' => 'required|between:1,50',
                'account' => 'required|between:6,16',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-50',
                'account.between' => ':attribute字符长度6-16',
            ];
            $replace = [
                'name' => '管理员称呼',
                'account' => '账号',
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

            $manager_user = ManagerUser::find($request->id);
            $manager_user->name = $request->name;
            $manager_user->account = $request->account;
            $res = $manager_user->save();
            if(!$res){
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }
            $manager_user->roles()->sync($request->role_ids);
            return response()->json(['status'=>1,'msg'=>'编辑成功']);
        }else{
            //载入当前管理员
            $manager_user = ManagerUser::find($request->id);
            $sign['manager_user'] = $manager_user;
            //载入当前管理员角色
            $user_roles = ManagerRoleUser::where('user_id',$request->id)->pluck('role_id')->toArray();
            $sign['manager_user_roles'] = $user_roles;
            //载入所有角色
            $roles = ManagerRole::all();
            $sign['roles'] = $roles;


            return view('admin.manager_user.edit',$sign);
        }
    }


    /**
     * 删除用户
     * @author my  2017-11-6
     */
    public function ajaxDel(Request $request){
        $user = ManagerUser::find($request->id);
        if(!$user){
            return response()->json(["status" => 0,"msg" => "参数错误"]);
        }
        $user->roles()->detach();//删除用户会附带删除它所对应的角色
        $user->delete();
        return response()->json(["status" => 1,"msg" => "删除成功"]);
    }


    /**
     * 显示一个用户的权限
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_page_powers(Request $request){
        $user = ManagerUser::find($request->id);
        $sign['power'] = $user->powerGroupList();
        $res = response()->view('admin.manager_role.powers',$sign)->getContent();
        if($res){
            return response()->json(['status'=>1,'msg'=>'获取成功','body'=>$res]);
        }else{
            return response()->json(['status'=>0,'msg'=>'获取失败']);
        }

    }


    public function ajax_repass(Request $request){
        $rule = [
            'password' => 'required|between:1,20',
        ];
        $message = [
            'required' => ':attribute不能为空',
            'password.between' => ':attribute字符长度1-20',
        ];
        $replace = [
            'password' => '密码',
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

        $user = ManagerUser::find($request->id);
        $user->password = bcrypt($request->passwrod);
        $res = $user->save();
        if(true == $res){
            return response()->json(['status'=>1,'msg'=>'密码修改成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'密码修改失败']);
        }
    }
}