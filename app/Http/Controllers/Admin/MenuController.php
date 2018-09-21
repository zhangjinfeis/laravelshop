<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Models\Menu;
use App\Models\Pic;

/**
 * 前台菜单控制器
 * @author my 2017-10-25
 * Class MenuController
 * @package App\Http\Controllers\Admin
 */
class MenuController extends Controller
{

    /**
     * 菜单列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $sign['list'] = Menu::getList();
        return view('admin/menu/index', $sign);
    }

    /**
     * 创建菜单
     * @author my  2017-10-25
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ajaxCreate(Request $request){
        $rule = [
            'name'=>'required|between:1,20',
            'target'=>'required|between:1,20'
        ];
        $message = [
            'required' => ':attribute不能为空',
            'name.between' => ':attribute字符长度1-20',
            'target.between' => ':attribute字符长度1-20'
        ];
        $replace = [
            'name'=>'菜单名称',
            'target'=>'打开方式',
        ];

        $validator = Validator::make($request->all(),$rule,$message,$replace);
        if ($validator->fails()){
            return response()->json(['status'=>0,'msg'=>$validator->errors()->first()]);
        }

        $data['name'] = $request->name;
        $data['url'] = $request->url;
        $data['target'] = $request->target;
        $data['is_show'] = $request->is_show;



        $menu = Menu::create($data);  //默认创建的都是根节点，创建根节点
        //dd($menu);

        if($request->parent_id){   //如果不是根节点，第二步：移动到对应位置
            $root = Menu::find($request->parent_id);
            $menu->makeChildOf($root);
        }

        //重置is_show
        Menu::resetIsShow();
        return response()->json(['status'=>1,'msg'=>'添加成功']);
    }

    /**
     * 删除菜单
     * @author my  2017-10-25
     * @param Request $request 请求
     * @return array
     */
    public function ajaxDel(Request $request){
        foreach($request->ids as $vo){
            $menu = Menu::find($vo);
            if($menu){
                $menu->delete();
            }
        }
        return ['status'=>1,'msg'=>'删除成功'];
    }


    /**
     * 开启
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxIsShow(Request $request){
        $res = Menu::whereIn('id',$request->ids)->update(['is_show'=>1]);
        if($res){
            //重置is_show
            Menu::resetIsShow();
            return response()->json(['status'=>1,'msg'=>'开启成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'开启失败']);
        };
    }

    /**
     * 关闭
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxUnShow(Request $request){
        $res = Menu::whereIn('id',$request->ids)->update(['is_show'=>9]);
        if($res){
            //重置is_show
            Menu::resetIsShow();
            return response()->json(['status'=>1,'msg'=>'关闭成功拉']);
        }else{
            return response()->json(['status'=>0,'msg'=>'关闭失败']);
        };
    }

    /**
     * 跳转到编辑菜单页面
     * @author my  2017-10-25
     * @param Request $request
     * @param Menu $menu 依赖注入的菜单模型
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        if($request->isMethod('post')){

            $rule = [
                'name'=>'required|between:1,20',
                'target'=>'required|between:1,20'
            ];
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-20',
                'target.between' => ':attribute字符长度1-20'
            ];
            $replace = [
                'name'=>'菜单名称',
                'target'=>'打开方式',
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
            $menu = Menu::find($request->id);
            $menu->name = $request->name;
            $menu->url = $request->url;
            $menu->target = $request->target;
            $menu->is_show = $request->is_show;
            if($menu->save()){
                //重置is_show
                Menu::resetIsShow();
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }else{
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }

        }else{
            $sign['menu'] = Menu::find($request->id);
            $sign['parent'] = Menu::find($sign['menu']->parent_id);
            return view('admin/menu/edit',$sign);
        }
    }

    /**
     * 移动菜单到某个菜单下
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxMove(Request $request){
        $menu = Menu::find($request->move_id);
        $to_menu = Menu::find($request->move_to_id);
        switch ($request->move_method){
            case 'child' :
                $res = $menu->makeChildOf($to_menu);
                break;
            case 'before':
                $res = $menu->moveToLeftOf($to_menu);
                break;
            case 'after':
                $res = $menu->moveToRightOf($to_menu);
                break;
        }
        if($res){
            //重置is_show
            Menu::resetIsShow();
            return response()->json(['status'=>1,'msg'=>'移动成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'移动失败']);
        };
    }

}