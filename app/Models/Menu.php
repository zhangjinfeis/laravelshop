<?php

namespace App\Models;

use Baum\Node;

/**
 * 前台菜单模型
 * @author my 2017-10-25
 * Class ManagerMenu
 * @package App
 * https://packagist.org/packages/baum/baum
 */
class Menu extends Node
{
    protected $table = 'menu';

    /**
     * 获取自身菜单以及所属所有的子节点
     * @author my  2017-11-3
     * @param int $id:菜单id  $must_show:false全部显示 true只显示is_show==1的
     * @return array
     */
    protected function getList($id=0,$must_show=false){
        if($id){
            $roots = SELF::where('id',$id)->get();
            if(!$roots) return [];
        }else{
            $roots = SELF::where("depth",0)->orderBy("lft","asc")->get();
        }
        $data = [];
        foreach ($roots as $k=>&$r){
            $data = array_merge($data,$r->getDescendantsAndSelf()->toArray());
        }
        foreach($data as $key => $v){
            if($must_show && $v['is_show'] == 9){
                unset($data[$key]);continue;
            }
            $data[$key]['depth_name'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$v['depth'] ).'└';
        }
        return $data;
    }


    /**
     * 获取树结构
     * @author zjf ${date}
     * @param int $id
     * @param bool $must_show
     * @return array
     */
    protected function getTree($id=0,$must_show=false){
        $list = $this->getList($id,$must_show);
        $array_depth = array_pluck($list, 'depth');
        for($i = max($array_depth);$i > min($array_depth);$i--){
            foreach($list as $k => $v){  //下一层
                if($v['depth'] == $i){
                    foreach($list as $key => $val){  //上一层
                        if($v['parent_id'] == $val['id']){
                            $list[$key]['child'][] = $v;
                        }
                    }
                }
            }
        }
        $res = [];
        foreach($list as $jav){
            if($jav['depth'] == min($array_depth)){
                array_push($res,$jav);
            }
        }
        return $res;
    }


    /**
     * 重置is_show，当is_show变动时调用
     * @author zjf ${date}
     */
    protected function resetIsShow(){
        //关闭is_show=9的
        $unshow = SELF::where('is_show',9)->get();
        if($unshow){
            foreach($unshow as $vo){
                $ids = $vo->getDescendants()->pluck('id')->toArray();
                SELF::whereIn('id',$ids)->update(['is_show'=>9]);
            }
        }
/*        //开启is_show=1的
        $isshow = SELF::where('is_show',1)->get();
        if($isshow){
            foreach($isshow as $vo){
                $ids1 = $vo->getDescendants()->pluck('id')->toArray();
                SELF::whereIn('id',$ids1)->update(['is_show'=>1]);
            }
        }*/
    }

}
