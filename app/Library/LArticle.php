<?php

namespace App\Library;
use App\Models\ArticleCate;
use App\Models\Article;
class LArticle{

    /**
     * 重置is_able
     * leaf节点可用  其他节点不可用
     * @author zjf ${date}
     */
    static function cate_resetIsAble(){
        $ids_is_able = [];
        $ids_un_able = [];
        $catelist = ArticleCate::get();
        foreach($catelist as $vo){
            if($vo->isLeaf()){
                array_push($ids_is_able,$vo->id);
            }else{
                array_push($ids_un_able,$vo->id);
            }
        }
        ArticleCate::whereIn('id',$ids_is_able)->update(['is_able'=>1]);
        ArticleCate::whereIn('id',$ids_un_able)->update(['is_able'=>9]);
        unset($ids_is_able);
        unset($ids_un_able);
    }

    /**
     * 重置is_show
     * 当is_show变动时调用
     * 找到关闭的节点，将其子节点设置为关闭
     * @author zjf ${date}
     */
    static function cate_resetIsShow(){
        //关闭is_show=9的
        $unshow = ArticleCate::where('is_show',9)->get();
        if($unshow){
            foreach($unshow as $vo){
                $ids = $vo->getDescendants()->pluck('id')->toArray();
                ArticleCate::whereIn('id',$ids)->update(['is_show'=>9]);
            }
        }
        unset($unshow);
    }
}


