<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
// use Session;
//获取图片验证码
class VerifyCodeController extends Controller
{
    //
    public function index(){
    	$str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$phraseBuilder = new PhraseBuilder(4,$str); //验证码长度,根字符
    	$builder = new CaptchaBuilder(null,$phraseBuilder);
        $builder->setBackgroundColor(255,255,255);
        $builder->setMaxBehindLines(0);
		$builder->build(); //宽,高,字体
		$phrase = $builder->getPhrase();
		//把内容存入session
		session(['verify_code'=>$phrase]);
		//ob_clean(); //清除缓存
		//header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
}
