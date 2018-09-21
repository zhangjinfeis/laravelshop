@extends("admin.include.mother")

@section("content")

    <div class="u-breadcrumb">
        <ol>
            <li>公共模块</li>
            <li>链接</li>
            <li>链接列表</li>
        </ol>
    </div>
    <div class="h20"></div>

    <a role="button" class="btn btn-sm btn-primary" href="{{url('/admin/city/create')}}"><i class="fa fa-plus"></i> 新增城市</a>
    <div class="h15"></div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>城市名称</th>
                <th>机构数</th>
                <th>排序</th>
                <th>开启/关闭</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach($list as $vo)
            <tr>
                <td>{{$vo->id}}</td>
                <td>
                    {{$vo->name}}
                </td>
                <td>
                    {{count($vo->units)}}
                    <a href="{{url('admin/unit').'?city_id='.$vo->id}}">进入</a>

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
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/city/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
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
        </tbody>
    </table>

    <script>

        //删除
        function del_menu(id){
            $boot.confirm({text:'删除当前城市？（注意：若当前城市下存在机构，则无法删除）'},function(){
                if(!id){
                    $boot.warn({text:'删除城市出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/city/ajax_del',
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