@extends('admin.include.mother_frame')
@section('content')

    <div class="h15"></div>
    <div class="m-login-head">
        <a href="" class="logo"><img src="/resources/admin/images/logo_login.png" /></a>
        <div class="name">内容管理系统</div>
    </div>

    <div class="h30"></div>
    <div class="h50"></div>
    <div class="m-login">
        <form action="/admin/login" method="post">
            {{ csrf_field() }}
            <div class="item">
                <input type="text" name="account" autocomplete="off" class="form-control form-control-lg js-text" placeholder="请输入账号" />
            </div>
            <div class="item">
                <input type="password" name="password" autocomplete="off" class="form-control form-control-lg js-text" placeholder="请输入密码" />
            </div>
            <div class="item">
                <input name="captcha" autocomplete="off" type="text" class="form-control form-control-lg js-text" placeholder="验证码" />
            </div>
            <div class="item">
                <div class="verify">
                    <img src="{{url("/get_verify_code")}}" onclick="$(this).attr('src','/get_verify_code?'+Math.random())" class="code-img" />
                </div>
            </div>
            <div class="h20"></div>
            <div class="custom-control custom-checkbox">
                <input name="remember" value="1" type="checkbox" class="custom-control-input" id="checkbox1">
                <label class="custom-control-label" for="checkbox1">记住登录状态</label>
            </div>
            <div class="h20"></div>
            <button class="btn btn-primary btn-block" type="submit">登录</button>
            {{--<div class="submit">登陆</div>--}}
        </form>
    </div>

    <div class="h50"></div>
    <div class="m-copyright">copyright&nbsp;©&nbsp;兰谷科技&nbsp;&nbsp;&nbsp; All Rights Reserved.</div>


    <script>
        //ajax提交登录
        $("form").submit(function(){
            $.ajax({
                type:'post',
                url:$('form').attr('action'),
                data:$('form').serialize(),
                success:function(res){
                    if(res.status == 0){
                        $boot.warn({text:res.msg},function(){
                            $('input[name='+res.field+']').focus();
                        });
                    }else{
                        $boot.success({text:res.msg},function(){
                            window.location = "{{url('/admin')}}";
                        });

                    }
                }
            });
            return false;
        });


        //输入框z-index切换
        $(".js-text").focus(function(){
            $(".js-text").css({'z-index':1});
            $(this).css({'z-index':2});
        })

    </script>
@stop
