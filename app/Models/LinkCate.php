<?php

namespace App\Models;

use Baum\Node;

/**
 * 链接模型
 * @author my 2017-10-25
 * Class ManagerMenu
 * @package App
 * https://packagist.org/packages/baum/baum
 */
class LinkCate extends Node
{
    protected $table = 'link_cate';


    /**
     * 分类下的链接
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links(){
        return $this->hasMany('App\Models\Link','cate_id','id');
    }

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



}
