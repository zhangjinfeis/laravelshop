<?php

namespace App\Models;

use Baum\Node;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
/**
 * 菜单模型
 * @author my 2017-10-25
 * Class ManagerMenu
 * @package App
 * https://packagist.org/packages/baum/baum
 */
class ArticleCate extends Node
{
    protected $table = 'article_cate';
    protected $guarded = [];
    /**
     * 分类下的文章
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(){
        return $this->hasMany('App\Models\Article','cate_id','id');
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
            $data[$key]['count'] = 0;
            $data[$key]['depth_name'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$v['depth'] ).'└';
        }
        $article = Article::select(DB::raw('count(cate_id) as count,cate_id'))->groupBy('cate_id')->get()->toArray();
        foreach($data as $kee => $vaa){
            foreach($article as $ke=>$vo){
                if($vaa['id'] == $vo['cate_id']){
                    $data[$kee]['count'] = $vo['count'];
                }
            }
        }
        return $data;
    }


    /**
     * 获取包含自身的子节点id集合
     * @author zjf ${date}
     * @param $id
     * @param bool $must_show
     * @return array
     */
    protected function getChildrenIdsAnfSelf($id,$must_show=false){
        if($must_show){
            return SELF::find($id)->descendantsAndSelf()->where('is_able',1)->pluck('id')->toArray();
        }else{
            return SELF::find($id)->descendantsAndSelf()->pluck('id')->toArray();
        }

    }






}
