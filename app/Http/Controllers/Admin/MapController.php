<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Models\Map;

/**
 * 后台菜单控制器
 * @author my 2017-10-25
 * Class MenuController
 * @package App\Http\Controllers\Admin
 */
class MapController extends Controller
{

    /**
     * 菜单列表
     * @author my  2017-10-25
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $where = [];
        if($request->title){
            $where[] = ['title','like','%'.$request->title.'%'];
        }
        $sign['list'] = Map::where($where)->orderBy('id','desc')->paginate(10);
        return view('admin/map/index', $sign);
    }

    /**
     * 创建地图
     * @author my  2017-10-25
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createEdit(Request $request){
        if($request->isMethod('post')){
            $rule = [
                'title' => 'required|between:1,100',
                'lng' => 'required',
                'lat' => 'required',
                'zoom' => 'required',
            ];
            $message = [
                'required' => ':attribute不能为空',
                'title.between' => ':attribute字符长度1-100',
            ];
            $replace = [
                'title' => '标题',
                'lng' => '经纬度',
                'lat' => '经纬度',
                'zoom' => '经纬度',
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
            $data['title'] = $request->title;
            $data['lng'] = $request->lng;
            $data['lat'] = $request->lat;
            $data['zoom'] = $request->zoom;
            $data['description'] = htmlspecialchars($request->description);
            $data['address'] = $request->address;
            $data['phone'] = $request->phone;
            $data['email'] = $request->email;
            $data['qq'] = $request->qq;
            if($request->id){
                $res = Map::where('id',$request->id)->update($data);
                if(!$res){
                    return response()->json(['status'=>0,'msg'=>'编辑失败']);
                }else{
                    return response()->json(['status'=>1,'msg'=>'编辑成功']);
                }
            }else{
                $res = Map::create($data);
                if(!$res){
                    return response()->json(['status'=>0,'msg'=>'新增失败']);
                }else{
                    return response()->json(['status'=>1,'msg'=>'新增成功']);
                }
            }

        }else{
            $sign = [];
            if($request->id){
                $sign['page'] = Map::find($request->id);
            }
            return view('admin.map.create_edit',$sign);
        }
    }

    /**
     * 删除地图
     * @author my  2017-10-25
     * @param Request $request 请求
     * @return array
     */
    public function ajaxDel(Request $request){
        $res = Map::whereIn('id',$request->ids)->delete();
        if($res){
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status'=>0,'msg'=>'删除失败'];
        }
    }

}