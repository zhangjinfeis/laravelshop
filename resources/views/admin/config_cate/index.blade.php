@extends("admin.include.mother")
@section('content')

    <div class="clearfix">
        <div class="float-left">
            <div class="u-breadcrumb">
                <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
                <span class="name">参数分类</span>
            </div>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-primary" onclick="return alert_win();"><i class="fa fa-plus"></i> 新增参数分类</button>
        </div>
    </div>
    <div class="h15"></div>

    <div id="as" style="display: none;">
        <div class="m-manager-menu-create">
            <input name="parent_id" type="hidden" value="">
            <div class="form-group">
                <label for="name"><span class="text-danger">* </span>分类名称</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="菜单名称">
                <small class="form-text text-muted">1-20个字符</small>
            </div>
            <div class="form-group">
                <label for="sort">排序</label>
                <input type="text" class="form-control" id="sort" name="sort" placeholder="排序" value="50">
                <small class="form-text text-muted">默认50,数值越小排名越靠前</small>
            </div>
            <div class="h10"></div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="return create_cate();">新增</button>
            </div>
        </div>
    </div>
    <script>
        //弹出创建菜单窗口
        function alert_win(){
            $boot.win({id:'#as','title':'新增参数分类'});
            return false;
        }


        //新增菜单提交
        function create_cate(){
            if(!$('input[name=name]').val()){
                $boot.warn({text:'分类名称不能为空'});
                return false;
            }
            var data = {
                name:$('input[name=name]').val(),
                sort:$('input[name=sort]').val(),
            };
            $.ajax({
                type:'post',
                url:'/admin/config_cate/ajax_create',
                data:data,
                success:function(res){
                    if(res.status == 0){
                        $boot.warn({text:res.msg});
                    }else{
                        $boot.success({text:res.msg},function(){
                            window.location = window.location;
                        });
                    }
                }
            });
            return false;
        }

    </script>

    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        @foreach($list as $vo)
            <tr>
                <td>{{$vo->id}}</td>
                <td>
                    {{$vo->name}}
                </td>
                <td>
                    {{$vo->sort}}
                </td>
                <td>
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/config_cate/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            更多
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="return del_menu({{$vo->id}});"><i class="fa fa-trash"></i> 删除</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    <script>

        //删除
        function del_menu(id){
            $boot.confirm({text:'确认删除当前分类？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/config_cate/ajax_del',
                    data:{id:id},
                    success:function(res){
                        if(res.status == 0){
                            $boot.warn({text:res.msg});
                        }else{
                            $boot.success({text:res.msg},function(){
                                window.location = window.location;
                            });

                        }
                    }
                });
            });
            return false;
        }
    </script>

@endsection