<?php
Route::group(['prefix' => 'mobi','namespace' => 'Mobi'], function() {

    Route::get('/', 'IndexController@index');  //首页

    Route::get('/about', 'IndexController@about');  //公司简介

    Route::get('/zizhi', 'IndexController@zizhi');  //公司资质

    Route::get('/huanjing', 'IndexController@huanjing');  //公司环境

    Route::get('/result', 'IndexController@result');  //企业业绩
    Route::get('/result/detail', 'IndexController@result_detail');  //企业业绩详情

    Route::get('/news', 'IndexController@news');  //新闻列表
    Route::get('/news/detail', 'IndexController@news_detail');  //新闻详情

    Route::get('/contact', 'IndexController@contact');  //联系我们

});