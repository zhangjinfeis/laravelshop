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
class LinkController extends Controller
{

    /**
     * 菜单列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $where = [];
        if($request->cate_id){
            $where['cate_id'] = $request->cate_id;
        }

        $sign['list'] = Link::where($where)->orderBy('sort','asc')->orderBy('id','desc')->paginate(10);
        return view('admin/link/index', $sign);
    }

    /**
     * 创建文章
     * @author my  2017-10-25
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'title' => 'required|between:1,100',
                'url' => 'required',
                'target' => 'required',
                'sort' => 'required',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'title.between' => ':attribute字符长度1-100',
            ];
            $replace = [
                'title' => '标题',
                'url' => '链接',
                'target' => '打开方式',
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
            //新增
            $data['cate_id'] = $request->cate_id;
            $data['title'] = $request->title;
            $data['thumb'] = $request->thumb;
            $data['url'] = $request->url;
            $data['target'] = $request->target;
            $data['sort'] = $request->sort;
            $data['is_show'] = $request->is_show;
            $link = Link::create($data);
            if(!$link){
                return response()->json(['status'=>0,'msg'=>'新增失败']);
            }else{
                return response()->json(['status'=>1,'msg'=>'新增成功']);
            }
        }else{
            //载入文章分类
            $sign['cate'] = LinkCate::getList();
            return view('admin.link.create',$sign);
        }
    }

    /**
     * 删除菜单
     * @author my  2017-10-25
     * @param Request $request 请求
     * @return array
     */
    public function ajaxDel(Request $request){
        //验证分类信息是否正确
        $manager_menu = Link::find($request->id);
        //执行删除操作
        if(isset($manager_menu)){//若存在则删除
            $manager_menu->delete();
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status'=>0,'msg'=>'删除失败，未找到文章'];
        }
    }

    /**
     * 文章编辑
     * @author my  2017-10-25
     * @param Request $request
     * @param LinkCate $menu 依赖注入的菜单模型
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'title' => 'required|between:1,100',
                'url' => 'required',
                'target' => 'required',
                'sort' => 'required',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'title.between' => ':attribute字符长度1-100',
            ];
            $replace = [
                'title' => '标题',
                'url' => '链接',
                'target' => '打开方式',
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
            //更新
            $data['cate_id'] = $request->cate_id;
            $data['title'] = $request->title;
            $data['thumb'] = $request->thumb;
            $data['url'] = $request->url;
            $data['target'] = $request->target;
            $data['sort'] = $request->sort;
            $data['is_show'] = $request->is_show;
            $link = Link::where('id',$request->id)->update($data);
            if(!$link){
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }else{
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }
        }else{
            //载入文章
            $article = Link::find($request->id);
            $sign['article'] = $article;
            //载入文章分类
            $sign['cate'] = LinkCate::getList();
            return view('admin.link.edit',$sign);
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