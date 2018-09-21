<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 文章模型
 * @author my 2017-10-25
 * Class ManagerMenu
 * @package App
 * https://packagist.org/packages/baum/baum
 */
class Article extends Model
{
    protected $table = 'article';
    protected $guarded = [];
    protected $dateFormat = 'U';


    /**
     * 文章的分类
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cate(){
        return $this->belongsTo('App\Models\ArticleCate','cate_id','id');
    }


    /**
     * 缩略图
     * @author zjf ${date}
     * @return mixed
     */
    public function thumb_pic(){
        return $this->belongsTo('App\Models\Pic','thumb','md5');
    }


    /**
     * 获取文章的exattr
     * @author zjf ${date}
     * @param $id
     * @return array
     */
    static function exattr($id){
        $arr = [];
        $article = SELF::find($id);
        $exattr_val = json_decode($article->exattr,true);
        $exattr = ArticleExattr::where('cate_id',$article->cate_id)->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
        foreach($exattr as $key => $value){
            $exattr[$key]['value'] = '';
            if($exattr_val){
                foreach($exattr_val as $k => $v){
                    if($value['key'] == $k){
                        $exattr[$key]['value'] = $v;
                    }
                }
            }
            $arr[$value['key']] = $exattr[$key];
        }
        unset($exattr);
        unset($exattr_val);
        return $arr;
    }

}
