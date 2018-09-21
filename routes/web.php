<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'Common'],function() {
    Route::get('get_verify_code', 'VerifyCodeController@index'); //获取图片验证码

    Route::get('/download/{md5}', 'DownloadController@downloadFile');  //下载文件
    Route::get('/image/{pic_md5}', 'ImageController@show');

});

include_once ("admin.php");
include_once ("mobi.php");