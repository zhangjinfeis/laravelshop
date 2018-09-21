<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 后台用户模型
 * @author my 2017-10-25
 * Class ManagerUser
 * @package App
 */
class ManagerUser extends Authenticatable
{
    protected $table = "manager_user";
    protected $dateFormat = 'U';
    use Notifiable;

    protected $fillable = [//可以注入的字段
        'name', 'password', 'account',
    ];

    protected $hidden = [//默认不查询出来的字段
        'password', 'remember_token',
    ];

    /**
     * 用户所有的角色
     * @author my  2017-10-25
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function roles(){
        return $this->belongsToMany(\App\Models\ManagerRole::class,'manager_role_user','user_id','role_id');
    }


    /**
     * 给用户分配角色
     * 每一次分配其实是将关系重新绑定并建立
     * 新增所有需要绑定的关系，同时删除所有不需要绑定的关系
     * @author my  2017-10-25
     * @param $role_ids
     * @return array(status=>状态1-成功0-失败,msg=>消息,data=>数据)
     */
    /*public function assignRole($role_ids)
    {
        try{
            //第一步清空关系
            $this->roles()->detach();
            //第二部赋予角色
            if(is_array($role_ids) && count($role_ids)>0){//有需要分配的角色
                $this->roles()->attach($role_ids, ['created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
            }
            return ['status'=>1,'msg'=>'','data'=>''];
        }catch (QueryException $e){
            return ['status'=>0,'msg'=>'分配角色失败，请重试','data'=>''];
        }

    }*/

    /**
     * 是否拥有某个角色
     * @author my  2017-10-25
     * @param $roles 角色集合
     * @return bool 是或否
     */
    public function isInRoles($roles)
    {
        //判断拥有这个权限的角色们，和当前用户所对应的角色们，是否有重叠，若有重叠，数字>0则返回true表示拥有权限，否则为0表示没有权限

        //intersect方法，用于集合对比，A->intersect(B)时，保留A与B中相同的，去掉A中不相同的

        //$roles 需要符合的角色集合
        //$this->roles 当前用户拥有的角色
        return !! $roles->intersect($this->roles)->count();
    }

    /**
     * 判断是否有权限
     * @author my  2017-10-25
     * @param $power 需要满足的权限集合
     * @return bool 返回有或没有
     */
    public function hasPower($power){
        return $this->isInRoles($power->roles);
    }

    /**
     * 获得当前用户的所有权限规则,用于can的判断
     * @author my  2017-10-25
     * @return array(status=>状态1-成功0-失败,msg=>消息,data=>数据)
     */
    public function powerList(){
        //用户拥有的角色
        $role_ids = ManagerRoleUser::where('user_id',$this->id)->pluck('role_id')->toArray();
        //角色拥有的权限
        $power_ids = ManagerPowerRole::whereIn('role_id',$role_ids)->pluck('power_id')->toArray();
        $power_ids = array_unique($power_ids);  //去重
        $powers = ManagerPower::whereIn('id',$power_ids)->pluck('name')->toArray();
        return $powers;
    }


    /**
     * 当前用户拥有的权限（按分组转成了二维数组）
     * @author zjf ${date}
     * @param $role_id
     * @return mixed
     */
    public function powerGroupList(){
        //用户拥有的角色
        $role_ids = ManagerRoleUser::where('user_id',$this->id)->pluck('role_id')->toArray();
        //角色拥有的权限
        $has_powers = ManagerPowerRole::whereIn('role_id',$role_ids)->pluck('power_id')->toArray();
        $has_powers = array_unique($has_powers);  //去重

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
