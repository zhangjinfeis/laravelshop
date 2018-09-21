@extends("admin.include.mother")

@section("content")

    <div class="clearfix">
        <div class="float-left">
            <div class="u-breadcrumb">
                <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
                <span class="name">链接列表</span>
            </div>
        </div>
        <div class="float-right">
            <a role="button" class="btn btn-sm btn-primary" href="{{url('/admin/link/create')}}"><i class="fa fa-plus"></i> 新增链接</a>
        </div>
    </div>
    <div class="h15"></div>


    <table class="table table-hover">
            <tr>
                <th>ID</th>
                <th>链接分类</th>
                <th>标题</th>
                <th>链接</th>
                <th>打开方式</th>
                <th>链接图片</th>
                <th>排序</th>
                <th>开启/关闭</th>
                <th>操作</th>
            </tr>
        @foreach($list as $vo)
            <tr>
                <td>{{$vo['id']}}</td>
                <td>
                    {{$vo->cate->name}}
                </td>
                <td>
                    {{$vo->title}}
                </td>
                <td>
                    {{$vo->url}}
                </td>
                <td>
                    {{$vo->target}}
                </td>
                <td>
                    @if($vo->thumb)
                        <img src="/image/{{$vo->thumb}}" height="30" />
                    @endif

                </td>
                <td>
                    {{$vo->sort}}
                </td>
                <td>
                    @if($vo['is_show'] ==1)
                        <span class="badge badge-success">开启</span>
                    @else
                        <span class="badge badge-danger">关闭</span>
                    @endif
                </td>
                <td>
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/link/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            更多
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="return del_menu({{$vo['id']}});"><i class="fa fa-trash"></i> 删除</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    {{$list->links()}}


    <script>

        //删除
        function del_menu(id){
            $boot.confirm({text:'确认删除当前文章？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/link/ajax_del',
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