<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * 文件模型
 * @author kevin 2017-11-08
 * Class Pic
 * @package App
 */
class Config extends Model
{
    protected $table = 'config';
    protected $guarded = [];
    protected $dateFormat = 'U';

    /**
     * 将配置转换成单条记录
     * @author zjf 2017-11-08
     * @param $md5
     * @return array
     */
    protected function toItem(){
        $list = $this->select('key','value')->get();
        foreach($list as $val){
            $data[$val->key] = $val->value;
        }
        return $data;
    }

}
