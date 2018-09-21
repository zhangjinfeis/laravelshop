@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
        <span class="name">新增管理员</span>
    </div>
    <div class="h30"></div>

    <form>
        <div class="form-group">
            <label for="name">管理员称呼</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="管理员称呼" style="width:400px;" value="" />
            <small class="form-text text-muted">1-50个字符</small>
        </div>
        <div class="form-group">
            <label for="account">账号</label>
            <input type="text" class="form-control" id="account" name="account" placeholder="账号" style="width:400px;" value="" />
            <small class="form-text text-muted">6-16个字符</small>
        </div>
        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="密码" style="width:400px;" value="" />
            <small class="form-text text-muted">6-16个字符</small>
        </div>
        <div class="form-group">
            <label for="name">关联角色</label>
            @foreach($roles as $vo)
                <div class="custom-control custom-checkbox">
                    <input name="role_ids[]" type="checkbox" class="custom-control-input" id="chcek{{$vo->id}}" value="{{$vo->id}}" />
                    <label class="custom-control-label" for="chcek{{$vo->id}}">{{$vo->name}}</label>
                </div>
            @endforeach
            <small class="form-text text-muted">可关联一个或多个角色</small>
        </div>
        <div class="h10"></div>
        <button type="submit" class="btn btn-primary" onclick="return post_create();">新增</button>
    </form>

    <script>
        //提交新增
        function post_create(){
            $.ajax({
                type:'post',
                url:'/admin/manager_user/create',
                data:$('form').serialize(),
                success:function(res){
                    if(res.status == 0){
                        $boot.warn({text:res.msg},function(){
                            $('input[name='+res.field+']').focus();
                        });
                    }else{
                        $boot.success({text:res.msg},function(){
                            window.location = "{{ url()->previous() }}";
                        });
                    }
                }
            })
            return false;
        }


    </script>
@endsection