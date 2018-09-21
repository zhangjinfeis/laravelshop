<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * 角色模型
 * @author my 2017-10-25
 * Class ManagerRole
 * @package App
 */
class ManagerRole extends Model
{
    protected $table = 'manager_role';
    protected $guarded = [];

    protected $dateFormat = 'U';

    /**
     * 找到角色对应的权限
     * @author my  2017-10-25
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function powers(){
        return $this->belongsToMany(\App\Models\ManagerPower::class,'manager_power_role','role_id','power_id');
    }

    /**
     * 找到角色对应的用户
     * @author my  2017-11-3
     * @return $this
     */
    public function users(){
        return $this->belongsToMany(\App\Models\ManagerUser::class,'manager_role_user','role_id','user_id');
    }


    /**
     * 当前角色拥有的权限（按分组转成了二维数组）
     * @author zjf ${date}
     * @param $role_id
     * @return mixed
     */
    public function powerGroupList($role_id){
        //角色拥有的权限
        $has_powers = ManagerPowerRole::where('role_id',$role_id)->pluck('power_id')->toArray();

        //载入权限（按分组）
        $groups = ManagerPower::select('group')->groupBy('group')->get()->toArray();
        $power = ManagerPower::orderBy('group','asc')->get()->toArray();

        foreach($power as $key => $val){
            $power[$key]['checked'] = in_array($val['id'],$has_powers)?'checked':'';
            foreach($groups as $k => $v){
                if($v['group'] == $val['group']){
                    $groups[$k]['child'][] = $power[$key];
                }
            }
        }
        return $groups;
    }
}
