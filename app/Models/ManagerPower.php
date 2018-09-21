<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 权限模型
 * @author my 2017-10-25
 * Class ManagerPower
 * @package App
 */
class ManagerPower extends Model
{
    protected $table = 'manager_power';
    protected $guarded = [];
    protected $dateFormat = 'U';

    /**
     * 获得拥有当前权限对应的角色
     * @author my  2017-10-25
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function roles(){
        return $this->belongsToMany(\App\Models\ManagerRole::class,'manager_power_role','power_id','role_id');
    }
}
