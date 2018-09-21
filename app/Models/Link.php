<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 链接模型
 * @author my 2017-10-25
 * Class ManagerMenu
 * @package App
 * https://packagist.org/packages/baum/baum
 */
class Link extends Model
{
    protected $table = 'link';
    protected $guarded = [];
    protected $dateFormat = 'U';


    /**
     * 链接的分类
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cate(){
        return $this->belongsTo('App\Models\LinkCate','cate_id','id');
    }


    /**
     * 缩略图片
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thumb_pic(){
        return $this->belongsTo('App\Models\Pic','thumb','md5');
    }

}
