<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Models\LinkCate;
use App\Models\Link;
use App\Models\Pic;

/**
 * 后台菜单控制器
 * @author my 2017-10-25
 * Class MenuController
 * @package App\Http\Controllers\Admin
 */
class LinkCateController extends Controller
{

    /**
     * 菜单列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $list = LinkCate::getList();
        foreach($list as $key => $val){
            $list[$key]['total'] = Link::where('cate_id',$val['id'])->count();
        }
        $sign['list'] = $list;
        return view('admin/link_cate/index', $sign);
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
        ];
        $message = [
            'required' => ':attribute不能为空',
            'name.between' => ':attribute字符长度1-20',
        ];
        $replace = [
            'name'=>'分类名称',
        ];

        $validator = Validator::make($request->all(),$rule,$message,$replace);
        if ($validator->fails()){
            return response()->json(['status'=>0,'msg'=>$validator->errors()->first()]);
        }

        $data['name'] = $request->name;
        $data['is_show'] = $request->is_show;

        $manager_menu = LinkCate::create($data);  //默认创建的都是根节点，创建根节点

        if($request->parent_id){   //如果不是根节点，第二步：移动到对应位置
            $root = LinkCate::find($request->parent_id);
            $manager_menu->makeChildOf($root);
        }
        return response()->json(['status'=>1,'msg'=>'添加成功']);
    }

    /**
     * 删除菜单
     * @author my  2017-10-25
     * @param Request $request 请求
     * @return array
     */
    public function ajaxDel(Request $request){
        //验证分类信息是否正确
        $manager_menu = LinkCate::find($request->id);
        //执行删除操作
        if(isset($manager_menu)){//若存在则删除
            $manager_menu->delete();
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status'=>0,'msg'=>'删除失败，未找到菜单'];
        }
    }

    /**
     * 跳转到编辑菜单页面
     * @author my  2017-10-25
     * @param Request $request
     * @param LinkCate $menu 依赖注入的菜单模型
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'name'=>'required|between:1,20',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'name.between' => ':attribute字符长度1-20',
            ];
            $replace = [
                'name'=>'分类名称',
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

            $menu = LinkCate::find($request->id);
            $menu->name = $request->name;
            $menu->is_show = $request->is_show;
            if($menu->save()){
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }else{
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }

        }else{
            $sign['menu'] = LinkCate::find($request->id);
            $sign['parent'] = LinkCate::find($sign['menu']->parent_id);
            return view('admin/link_cate/edit',$sign);
        }
    }

    /**
     * 移动菜单到某个菜单下
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxMove(Request $request){
        $menu = LinkCate::find($request->move_id);
        $to_menu = LinkCate::find($request->move_to_id);
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
            return response()->json(['status'=>1,'msg'=>'移动成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'移动失败']);
        };
    }

}