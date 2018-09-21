<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();
        $powers = \App\Models\ManagerPower::with('roles')->get();//得到所有权限列表
        foreach($powers as $power){//对每一个权限使用Gate进行注册，判断用户是否拥有此权限
            Gate::define($power->name, function ($user) use($power){
                /*$is_admin = $user->roles()->where('name','=','超级管理员')->first();
                if($is_admin){
                    return true;
                }*/
                return false;
                return $user->hasPower($power);
            });
        }

        //
    }
}
