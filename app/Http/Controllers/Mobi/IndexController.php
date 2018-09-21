<?php

namespace App\Http\Controllers\Mobi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Article;
use App\Models\Pic;
use Validator;


/**
 * 首页模块
 * @author my 2017-10-25
 * Class MenuController
 * @package App\Http\Controllers\Admin
 */
class IndexController extends Controller
{

    /**
     * 首页
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        //首页banner
        $banner = Link::where('cate_id',1)->orderBy('sort','asc')->orderBy('id','desc')->get();
        $sign['banner'] = $banner;
        //公司简介
        $about = Article::where('id',1)->first();
        $sign['about'] = $about;

        //企业业绩
        $result = Article::where('cate_id',4)->orderBy('sort','asc')->orderBy('id','desc')->limit(3)->get();
        foreach($result as $key => $val){
            $md5s = explode(',',$val['thumbs']);
            $result[$key]->thumbs_arr = Pic::whereIn('md5',$md5s)->get();
        }
        $sign['result'] = $result;

        //新闻
        $news = Article::where('cate_id',5)->orderBy('sort','asc')->orderBy('id','desc')->limit(3)->get();
        $sign['news'] = $news;

        //联系我们
        $contact = Article::where('id',11)->first();
        $sign['contact'] = $contact;

        return view('mobi.index.index',$sign);
    }


    /**
     * 公司简介
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about(Request $request){
        $about = Article::where('id',1)->first();
        $sign['page'] = $about;
        return view('mobi.index.about',$sign);
    }


    /**
     * 公司资质
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function zizhi(Request $request){
        $list = Article::where('cate_id',2)->orderBy('sort','asc')->orderBy('id','desc')->paginate(10);
        $sign['list'] = $list;
        return view('mobi.index.zizhi',$sign);
    }

    /**
     * 公司环境
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function huanjing(Request $request){
        $list = Article::where('cate_id',3)->orderBy('sort','asc')->orderBy('id','desc')->paginate(10);
        $sign['list'] = $list;
        return view('mobi.index.huanjing',$sign);
    }

    /**
     * 企业业绩
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function result(Request $request){
        $list = Article::where('cate_id',4)->orderBy('sort','asc')->orderBy('id','desc')->paginate(10);
        foreach($list as $key => $val){
            $md5s = explode(',',$val['thumbs']);
            $list[$key]->thumbs_arr = Pic::whereIn('md5',$md5s)->get();
        }
        $sign['list'] = $list;
        return view('mobi.index.result',$sign);
    }

    /**
     * 企业业绩详情
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function result_detail(Request $request){
        $page = Article::where('id',$request->id)->first();
        $sign['page'] = $page;
        return view('mobi.index.result_detail',$sign);
    }

    /**
     * 新闻中心
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news(Request $request){
        $list = Article::where('cate_id',5)->orderBy('sort','asc')->orderBy('id','desc')->paginate(10);
        $sign['list'] = $list;
        return view('mobi.index.news',$sign);
    }

    /**
     * 新闻详情
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news_detail(Request $request){
        $page = Article::where('id',$request->id)->first();
        $sign['page'] = $page;
        return view('mobi.index.news_detail',$sign);
    }

    /**
     * 新闻详情
     * @author zjf ${date}
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact(Request $request){
        $page = Article::where('id',11)->first();
        $sign['page'] = $page;
        return view('mobi.index.contact',$sign);
    }

}