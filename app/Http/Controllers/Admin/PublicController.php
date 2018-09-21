<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class PublicController extends Controller
{
    /**
     * 后台登录操作
     * @author zjf  2018-03-16
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'account' => 'required|between:2,16',
                'password' => 'required|between:6,16',
                'captcha' => 'required'
            ];
            $message = [
                'required' => ':attribute不能为空',
                'account.between' => ':attribute长度为2-16个字符',
                'password.between' => ':attribute长度为6-16个字符'
            ];
            $replace = [
                'account' => '账号',
                'password' => '密码',
                'captcha' => '验证码'
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
            //验证码
            if(strtolower($request->captcha) != strtolower(session('verify_code'))){
                return response()->json(['status'=>0,'msg'=>'验证码不正确','field'=>'captcha']);
            }

            //记住登录状态
            $remember = isset($request->remember);

            $user = request(['account', 'password']);
            if (true == \Auth::guard('admin')->attempt($user,$remember)) {
                return response()->json(['status'=>1,'msg'=>'系统登录中']);
            }

            return response()->json(['status'=>0,'msg'=>'用户名或密码错误','field'=>'name']);
        }else{
            return view("admin/public/login");
        }
    }


    public function logout(Request $request){
        \Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }



}
