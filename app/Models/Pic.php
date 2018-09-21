<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * 图片模型
 * @author my 2017-10-26
 * Class Pic
 * @package App
 */
class Pic extends Model
{
    protected $table = 'pic';
    protected $guarded = [];

    protected $dateFormat = 'U';

    /**
     * 获取用于图片上传
     * @author zjf 2017-11-03
     * @param $id
     * @return mixed
     */
    protected function getUploadById($id){
        return $this->select('width','height','size','md5')->where('id',$id)->first();
    }

    /**
     * 获取用于图片上传的图片组
     * @author zjf 2017-11-03
     * @param $ids
     * @return mixed
     */
    protected function getUploadByIds($ids){
        $ids = explode(',',$ids);
        return $this->select('width','height','size','md5')->whereIn('id',$ids)->get();
    }

    /**
     * 获取用于图片上传
     * @author zjf 2017-11-07
     * @param $md5
     * @return mixed
     */
    protected function getUploadByMd5($md5){
        return $this->select('width','height','size','md5')->where('md5',$md5)->first();
    }

    /**
     * 获取用于图片上传
     * @author zjf 2017-11-07
     * @param $md5s
     * @return mixed
     */
    protected function getUploadByMd5s($md5s){
        $md5s = explode(',',$md5s);
        return $this->select('width','height','size','md5')->whereIn('md5',$md5s)->first();
    }

    /**
     * 处理图片状态的方法
     * @author my  2017-11-3
     * @param $not_use 不使用的图片md5数组
     * @param $use 要使用的图片的md5
     * @param $not_use_html 匹配html中的不使用图片的md5
     * @param $use_html 匹配html中的要使用图片的md5
     * @return bool 成功or失败
     */
    protected function processPic($not_use,$use,$not_use_html="",$use_html=""){
        $pic_not_use_id = "";
        if($not_use){
            foreach ($not_use as $k=>$n){
                if($k>0){
                    $pic_not_use_id.=",".$n;
                }else {
                    $pic_not_use_id.=$n;
                }
            }
            $pic_not_use_id = array_unique(explode(",",$pic_not_use_id));
        }

        $pic_use_id = "";
        if($use){
            foreach ($use as $k=>$u){
                if($k>0){
                    $pic_use_id.=",".$u;
                }else {
                    $pic_use_id.=$u;
                }
            }
            $pic_use_id = array_unique(explode(",",$pic_use_id));
        }

        if(!empty($use_html)){
            preg_match_all('/<img\s.*?src=[\"\']\/image\/(.+?)[\'\"].*?\/?>/i', $use_html, $res);
            if(is_array($pic_use_id)){
                $pic_use_id = array_merge($pic_use_id,$res[1]);
            }else {
                $pic_use_id = $res[1];
            }
        }
        if(!empty($not_use_html)){
            preg_match_all('/<img\s.*?src=[\"\']\/image\/(.+?)[\'\"].*?\/?>/i', $not_use_html, $res);
            if(is_array($pic_not_use_id)){
                $pic_not_use_id = array_merge($pic_not_use_id,$res[1]);
            }else {
                $pic_not_use_id = $res[1];
            }
        }

        if(is_array($pic_not_use_id)){
            \App\Models\Pic::whereIn('md5',$pic_not_use_id)->update(['status' => 0]);
        }
        if(is_array($pic_use_id)){
            \App\Models\Pic::whereIn('md5',$pic_use_id)->update(['status' => 1]);
        }
        return true;
    }
}
