<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stadium;
use App\Models\StadiumCate;
use Validator;


/**
 * 后台菜单控制器
 * @author my 2017-10-25
 * Class MenuController
 * @package App\Http\Controllers\Admin
 */
class StadiumController extends Controller
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
        if($request->name){
            $where['name'] = ['like','%'.$request->name.'%'];
        }

        $sign['list'] = Stadium::with('cate')->where($where)->orderBy('updated_at','desc')->paginate(15);
        $sign['cate'] = StadiumCate::getList();
        return view('admin/stadium/index', $sign);
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
                'name' => 'required|between:1,100',
                'thumb' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'price' => 'required',
                'lng' => 'required',
                'lat' => 'required',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'title.between' => ':attribute字符长度1-100',
            ];
            $replace = [
                'name' => '场馆名称',
                'thumb' => '场馆图片',
                'address' => '地址',
                'phone' => '电话',
                'price' => '价格',
                'lng' => '经纬度',
                'lat' => '经纬度',
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
            $article['cate_id'] = $request->cate_id;
            $article['name'] = $request->name;
            $article['thumb'] = $request->thumb;
            $article['address'] = $request->address;
            $article['phone'] = $request->phone;
            $article['price'] = $request->price;
            $article['lng'] = $request->lng;
            $article['lat'] = $request->lat;
            $article['body'] = $request->body;
            $article['is_show'] = $request->is_show;
            $article = Stadium::create($article);
            if(!$article){
                return response()->json(['status'=>0,'msg'=>'新增失败']);
            }else{
                return response()->json(['status'=>1,'msg'=>'新增成功']);
            }
        }else{
            //载入文章分类
            $sign['cate'] = StadiumCate::getList();
            return view('admin.stadium.create',$sign);
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
        $manager_menu = Stadium::find($request->id);
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
     * @param StadiumCate $menu 依赖注入的菜单模型
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        if($request->isMethod('post')){

            $rule = [
                'name' => 'required|between:1,100',
                'thumb' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'price' => 'required',
                'lng' => 'required',
                'lat' => 'required',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'title.between' => ':attribute字符长度1-100',
            ];
            $replace = [
                'name' => '场馆名称',
                'thumb' => '场馆图片',
                'address' => '地址',
                'phone' => '电话',
                'price' => '价格',
                'lng' => '经度',
                'lat' => '纬度',
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
            $article['cate_id'] = $request->cate_id;
            $article['name'] = $request->name;
            $article['thumb'] = $request->thumb;
            $article['address'] = $request->address;
            $article['phone'] = $request->phone;
            $article['price'] = $request->price;
            $article['lng'] = $request->lng;
            $article['lat'] = $request->lat;
            $article['body'] = $request->body;
            $article['is_show'] = $request->is_show;
            $a = Stadium::where('id',$request->id)->update($article);
            if(!$a){
                return response()->json(['status'=>0,'msg'=>'编辑失败']);
            }else{
                return response()->json(['status'=>1,'msg'=>'编辑成功']);
            }
        }else{
            //载入文章
            $article = Stadium::find($request->id);
            $sign['article'] = $article;
            //载入文章分类
            $sign['cate'] = StadiumCate::getList();
            return view('admin.stadium.edit',$sign);
        }
    }

    /**
     * 移动菜单到某个菜单下
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxMove(Request $request){
        $menu = StadiumCate::find($request->move_id);
        $to_menu = StadiumCate::find($request->move_to_id);
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