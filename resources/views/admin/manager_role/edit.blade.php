@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
        <span class="name">编辑角色</span>
    </div>
    <div class="h30"></div>



    <form>
        <input name="id" type="hidden" value="{{$role->id}}" />
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="name">角色名称</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="角色名称" value="{{$role->name or ''}}" />
                    <small class="form-text text-muted">1-50个字符</small>
                </div>

                <div class="form-group">
                    <label for="url">描述</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="描述" value="{{$role->description or ''}}">
                </div>
                <div class="h10"></div>
                <button type="submit" class="btn btn-primary" onclick="return post_edit();">保存</button>
            </div>
            <div class="col-8">
                <label>选择权限</label>
                @foreach($power as $vo)
                    <div>
                        <div>{{$vo['group']}}</div>
                        <div>
                            @foreach($vo['child'] as $voo)
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="power[]" type="checkbox" class="custom-control-input" id="chcek{{$voo['id']}}" value="{{$voo['id']}}" {{$voo['checked']}} />
                                    <label class="custom-control-label" for="chcek{{$voo['id']}}">{{$voo['description']}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>

    <script>
        //提交新增
        function post_edit(){
            $.ajax({
                type:'post',
                url:'/admin/manager_role/edit',
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