<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\ManagerMenu;
use App\Models\ManagerPower;
use App\Models\Menu;
use App\Models\Config;
use App\Models\Article;
use App\Models\Link;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {


        Schema::defaultStringLength(191);
        //对admin.home.home模板进行渲染时，拿到所有的菜单，并且判断哪些可以展示出来，最终传递到页面中去
        \View::composer('admin.frame.index', function($view){

            $user = \Auth::guard("admin")->user();//拿到当前登录的用户

            //当前用户拥有的权限
            if($user->name == '超级管理员'){
                $user_powers = ManagerPower::pluck('name')->toArray();
            }else{
                $user_powers = $user->powerList();
            }

            $menu_depth_2 = ManagerMenu::with(['power'])->where('depth','2')->get();//拿到所有的2级菜单

            $depth_2 = [];
            $depth_1 = [];
            $depth_0 = [];



            foreach($menu_depth_2 as $m){
                if(in_array($m->power->name,$user_powers)){
                    $depth_2[$m->id] = "show";
                    $depth_1[$m->parent_id] = "show";
                }
            }


            $menu_depth_1 = ManagerMenu::where('depth','1')->get();//拿到所有的1级菜单
            foreach($menu_depth_1 as $m){
                if(in_array($m->id,array_keys($depth_1))){
                    $depth_0[$m->parent_id] = "show";
                }
            }

            $all =ManagerMenu::where('depth','0')->orderBy("lft","asc")->whereIn("id",array_keys($depth_0))->get();//拿出所有的顶级菜单列表
            $data = [];
            //顶级菜单列表进行循环遍历，拿到他嵌套的分支数据
            $whereIn = array_merge(array_keys($depth_2),array_keys($depth_1),array_keys($depth_0));

            foreach ($all as $k=>$d){
                $data = array_merge($data,$d->descendantsAndSelf()->with("power")->whereIn("id",$whereIn)->get()->toHierarchy()->toArray());
            }

            //config配置项
            $config = Config::toItem();
            $view->with(['menus'=> $data,'admin'=>$user,'config'=>$config]);
        });


        //前台页面写入导航信息
        \View::composer('mobi/include/header', function($view){
            $menu1 = Menu::getTree(6,true);
            $menu1 = $menu1[0]['child'];
            $view->with(['menu1'=>$menu1]);
        });

        //全局写入配置信息
        \View::composer('*', function($view){
            //config配置项
            $config = Config::toItem();
            $view->with(['config'=>$config]);
        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
