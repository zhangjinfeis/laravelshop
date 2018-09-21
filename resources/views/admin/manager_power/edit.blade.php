@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <ol>
            <li>首页</li>
            <li>权限&菜单</li>
            <li><a href="/admin/manager_power">权限</a></li>
            <li>编辑</li>
        </ol>
    </div>
    <div class="h30"></div>


    <form>
        <input type="hidden" name="id" value="{{$power->id}}" />
        <input name="parent_id" type="hidden" value="">
        <div class="form-group">
            <label for="name">权限名称</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="权限名称" style="width:400px;" value="{{$power->name}}" />
            <small class="form-text text-muted">1-50个字符</small>
        </div>

        <div class="form-group">
            <label for="url">描述</label>
            <input type="text" class="form-control" id="description" name="description" placeholder="路径" style="width:400px;" value="{{$power->description}}">
        </div>
        <div class="form-group">
            <label for="power_id">标签分组</label>
            <input type="text" class="form-control" id="group" name="group" placeholder="标签分组" style="width:400px;" value="{{$power->group}}">
        </div>

        <div class="h10"></div>
        <button type="submit" class="btn btn-primary" onclick="return post_edit();">保存</button>
    </form>

    <script>
        //提交编辑
        function post_edit(){
            $.ajax({
                type:'post',
                url:'/admin/manager_power/edit',
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