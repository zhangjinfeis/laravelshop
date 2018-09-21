<?php
Route::group(['prefix' => 'admin','namespace' => 'Admin'], function() {

    /*不需要登录的*/
    Route::match(['get','post'],'/login', 'PublicController@login')->name('login')->middleware('guest:admin');  //guest:admin 登录了跳转到内页
    Route::match(['get'],'/logout', 'PublicController@logout');



    /*需要登录的*/
    Route::group(['middleware' => 'auth:admin'], function(){
        //后台框架
        Route::match(['get'],'/', 'FrameController@index');

        // ———————————————————————————后台菜单管理—————————————————————————————————
        Route::group(['prefix' => 'manager_menu'],function(){
            Route::get('/', 'ManagerMenuController@index')->middleware('can:manager_menu');  //菜单列表
            Route::match(['post'],'/ajax_create', 'ManagerMenuController@ajaxCreate');//->middleware('can:manager_menu_create');  //创建菜单
            Route::match(['get','post'],'/edit','ManagerMenuController@edit');//->middleware('can:manager_menu_edit');  //编辑菜单
            Route::match(['post'],'/ajax_del','ManagerMenuController@ajaxDel');//->middleware('can:manager_menu_del');  //删除菜单
            Route::match(['post'],'/ajax_move','ManagerMenuController@ajaxMove');//->middleware('can:manager_menu_move');  //移动菜单
        });

        // ———————————————————————————权限管理—————————————————————————————————
        Route::group(['prefix' => 'manager_power'],function(){
            Route::get('/', 'ManagerPowerController@index');//->middleware('can:manager_power');  //权限列表
            Route::match(['post'],'/ajax_create', 'ManagerPowerController@ajaxCreate');//->middleware('can:manager_power_create');  //新增权限
            Route::match(['get','post'],'/edit','ManagerPowerController@edit');//->middleware('can:manager_power_edit');  //编辑权限
            Route::match(['post'],'/ajax_del','ManagerPowerController@ajaxDel');//->middleware('can:manager_power_del');  //删除权限
        });

        // ———————————————————————————角色管理—————————————————————————————————
        Route::group(['prefix' => 'manager_role'],function(){
            Route::get('/', 'ManagerRoleController@index');//->middleware('can:manager_role');  //角色列表
            Route::match(['get','post'],'/create', 'ManagerRoleController@create');//->middleware('can:manager_role_create');  //新增角色
            Route::match(['get','post'],'/edit','ManagerRoleController@edit');//->middleware('can:manager_role_edit');  //编辑角色
            Route::match(['post'],'/ajax_del','ManagerRoleController@ajaxDel');//->middleware('can:manager_role_del');  //删除角色
            Route::match(['post'],'/ajax_page_powers','ManagerRoleController@ajax_page_powers');//->middleware('can:manager_role_powers');  //显示权限
        });

        // ———————————————————————————管理员—————————————————————————————————
        Route::group(['prefix' => 'manager_user'],function(){
            Route::get('/', 'ManagerUserController@index');  //管理员列表
            Route::match(['get','post'],'/create', 'ManagerUserController@create');  //新增管理员
            Route::match(['get','post'],'/edit','ManagerUserController@edit');  //编辑管理员
            Route::match(['post'],'/ajax_del','ManagerUserController@ajaxDel');  //删除管理员
            Route::match(['post'],'/ajax_page_powers','ManagerUserController@ajax_page_powers');  //显示权限
            Route::match(['post'],'/ajax_repass','ManagerUserController@ajax_repass');  //修改密码
        });

        // ———————————————————————————网站导航—————————————————————————————————
        Route::group(['prefix' => 'menu'],function(){
            Route::get('/', 'MenuController@index');  //菜单列表
            Route::match(['post'],'/ajax_create', 'MenuController@ajaxCreate');  //创建菜单
            Route::match(['get','post'],'/edit','MenuController@edit');  //编辑菜单
            Route::match(['post'],'/ajax_del','MenuController@ajaxDel');  //删除菜单
            Route::match(['post'],'/ajax_move','MenuController@ajaxMove');  //移动菜单
            Route::match(['post'],'/ajax_is_show','MenuController@ajaxIsShow');  //开启菜单
            Route::match(['post'],'/ajax_un_show','MenuController@ajaxUnShow');  //关闭菜单
        });

        // ———————————————————————————文章分类—————————————————————————————————
        Route::group(['prefix' => 'article_cate'],function(){
            Route::get('/', 'ArticleCateController@index');  //分类列表
            Route::match(['post'],'/ajax_create', 'ArticleCateController@ajaxCreate');  //创建分类
            Route::match(['get','post'],'/edit','ArticleCateController@edit');  //编辑分类
            Route::match(['post'],'/ajax_del','ArticleCateController@ajaxDel');  //删除分类
            Route::match(['post'],'/ajax_move','ArticleCateController@ajaxMove');  //移动分类
            Route::match(['post'],'/ajax_move_content','ArticleCateController@ajaxMoveContent');  //移动文章内容
            Route::match(['post'],'/ajax_is_show','ArticleCateController@ajaxIsShow');  //开启分类
            Route::match(['post'],'/ajax_un_show','ArticleCateController@ajaxUnShow');  //关闭分类
        });

        // ———————————————————————————文章附加字段—————————————————————————————————
        Route::group(['prefix' => 'article_exattr'],function(){
            Route::match(['post'],'/create', 'ArticleExattrController@create');  //创建附加字段
            Route::match(['get','post'],'/edit', 'ArticleExattrController@edit');  //编辑附加字段
            Route::match(['post'],'/ajax_del','ArticleExattrController@ajaxDel');  //删除附加字段
        });

        // ———————————————————————————文章—————————————————————————————————
        Route::group(['prefix' => 'article'],function(){
            Route::get('/', 'ArticleController@index');  //文章列表
            Route::match(['get','post'],'/create', 'ArticleController@create');  //创建文章
            Route::match(['get','post'],'/edit','ArticleController@edit');  //编辑文章
            Route::match(['post'],'/ajax_del','ArticleController@ajaxDel');  //删除文章
            Route::match(['post'],'/ajax_move','ArticleController@ajaxMove');  //移动文章
            Route::match(['post'],'/ajax_exattr','ArticleController@ajaxExattr');  //ajax获取附加字段[,值]
        });

        // ———————————————————————————链接分类—————————————————————————————————
        Route::group(['prefix' => 'link_cate'],function(){
            Route::get('/', 'LinkCateController@index');  //菜单列表
            Route::match(['post'],'/ajax_create', 'LinkCateController@ajaxCreate');  //创建菜单
            Route::match(['get','post'],'/edit','LinkCateController@edit');  //编辑菜单
            Route::match(['post'],'/ajax_del','LinkCateController@ajaxDel');  //删除菜单
            Route::match(['post'],'/ajax_move','LinkCateController@ajaxMove');  //移动菜单
        });

        // ———————————————————————————链接—————————————————————————————————
        Route::group(['prefix' => 'link'],function(){
            Route::get('/', 'LinkController@index');  //菜单列表
            Route::match(['get','post'],'/create', 'LinkController@create');  //创建菜单
            Route::match(['get','post'],'/edit','LinkController@edit');  //编辑菜单
            Route::match(['post'],'/ajax_del','LinkController@ajaxDel');  //删除菜单
        });

        // ———————————————————————————留言—————————————————————————————————
        Route::group(['prefix' => 'guestbook'],function(){
            Route::get('/', 'GuestbookController@index');  //留言列表
        });

        // ———————————————————————————地图—————————————————————————————————
        Route::group(['prefix' => 'map'],function(){
            Route::get('/', 'MapController@index');  //地图列表
            Route::match(['post','get'],'/create_edit', 'MapController@createEdit');  //地图新增+编辑
            Route::match(['post'],'/ajax_del','MapController@ajaxDel');  //删除地图
        });

        //————————————————————————参数分类—————————————————————————————————————————
        Route::group(['prefix'=>'config_cate'],function(){
            Route::get('/','ConfigCateController@index');
            Route::match(['post'],'ajax_create','ConfigCateController@ajaxCreate');
            Route::match(['get','post'],'edit','ConfigCateController@edit');
            Route::post('ajax_del','ConfigCateController@ajaxDel');
        });

        //————————————————————————参数—————————————————————————————————————————
        Route::group(['prefix'=>'config'],function(){
            Route::match(['get','post'],'/','ConfigController@index');
            Route::match(['post'],'ajax_create','ConfigController@ajaxCreate');
            Route::match(['get','post'],'edit','ConfigController@edit');
            Route::match(['post'],'ajax_edit_value','ConfigController@ajaxEditValue');
            Route::post('ajax_del','ConfigController@ajaxDel');
        });

        // ———————————————————————————文件上传管理——————————————————————————————
        Route::group(['prefix'=>'upload'],function(){
            Route::post('/ajax_upload_img', 'UploadController@ajaxUploadImg'); //图片上传
            Route::post('/ajax_upload_file', 'UploadController@ajaxUploadFile'); //文件上传
            Route::post('/ajax_ckeditor_img','UploadController@ajaxCkeditorImg');//ckeditor的图片上传
        });



    });






});