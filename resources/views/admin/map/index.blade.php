@extends("admin.include.mother")

@section("content")

    <div class="clearfix">
        <div class="float-left">
            <div class="u-breadcrumb">
                <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
                <span class="name">地图列表</span>
            </div>
        </div>
        <div class="float-right">
            <a role="button" class="btn btn-sm btn-primary" href="{{url('/admin/map/create_edit')}}"><i class="fa fa-plus"></i> 新增地图</a>
        </div>
    </div>
    <div class="h15"></div>

    <table class="table table-hover" style="border-bottom:#dee2e6 1px solid;">

            <tr>
                <th width="40">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkbox-all" id="checkbox-0">
                        <label class="custom-control-label"  for="checkbox-0"></label>
                    </div>
                </th>
                <th>ID</th>
                <th>地图标题</th>
                <th>经/纬度</th>
                <th>地址</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>

        @foreach($list as $vo)
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{$vo->id}}" data-id="{{$vo->id}}">
                        <label class="custom-control-label"  for="checkbox-{{$vo->id}}"></label>
                    </div>
                </td>
                <td>{{$vo->id}}</td>
                <td>
                    {{$vo->title}}

                </td>
                <td>
                    {{$vo->lng}}/{{$vo->lat}}
                </td>
                <td>
                    {{$vo->address}}
                </td>
                <td>
                    {{$vo->created_at->format('Y-m-d H:i')}}
                </td>
                <td>
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/map/create_edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            更多
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="return del_one({{$vo['id']}});"><i class="fa fa-trash"></i> 删除</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach

    </table>
    <div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="return ajax_del_all();">删除</button>
    </div>
    {{$list->links()}}

    <script>
        //删除文章all
        function ajax_del_all(){
            var ids = [];
            $('.checkbox-item').filter(':checked').each(function(){
                ids.push($(this).attr('data-id'));
            });
            if(ids.length < 1){
                $boot.error({text:'请至少选择一个选项'});
                return false;
            }
            $boot.confirm({text:'确认删除所选？'},function(){
                $.ajax({
                    type:'post',
                    url:'/admin/map/ajax_del',
                    data:{ids:ids},
                    success:function(res){
                        if(res.status == 0){
                            $boot.error({text:res.msg});
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

        //删除一篇文章
        function del_one(id){
            $boot.confirm({text:'确认删除当前文章？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/map/ajax_del',
                    data:{ids:[id]},
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