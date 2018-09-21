@extends("admin.include.mother")

@section("content")

    <div class="u-breadcrumb">
        <ol>
            <li>场馆列表</li>
        </ol>
    </div>
    <div class="h20"></div>

    <a role="button" class="btn btn-sm btn-primary" href="{{url('/admin/stadium/create')}}"><i class="fa fa-plus"></i> 新增场馆</a>
    <div class="h15"></div>

    <form class="form-inline">
        <label for="name">场馆分类</label>&nbsp;
        <select class="form-control form-control-sm" id="cate_id" name="cate_id">
            @foreach($cate as $vo)
                @if(isset($_GET['cate_id']) && $vo['id'] == $_GET['cate_id'])
                    <option selected value="{{$vo['id']}}">{{$vo['depth_name']}}{{$vo['name']}}</option>
                @else
                    <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name']}}</option>
                @endif
            @endforeach
        </select>
        &nbsp;&nbsp;&nbsp;
        <label for="name">场馆名称</label>&nbsp;
        <input type="text" class="form-control form-control-sm mr-2" id="name" name="name" placeholder="输入场馆名称" value="{{request('name')}}">

        <button type="submit" class="btn btn-sm btn-primary">筛选</button>
    </form>
    <div class="h15"></div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>场馆分类</th>
                <th>场馆名称</th>
                <th>地址</th>
                <th>电话</th>
                <th>价格</th>
                <th>经纬度</th>
                <th>开启/关闭</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach($list as $vo)
            <tr>
                <td>{{$vo->id}}</td>
                <td>
                    {{$vo->cate->name}}
                </td>
                <td>
                    {{$vo->name}}
                </td>
                <td>
                    {{$vo->address}}
                </td>
                <td>
                    {{$vo->phone}}
                </td>
                <td>
                    {{$vo->price}}
                </td>
                <td>
                    {{$vo->lng or '-'}}/{{$vo->lat or '-'}}
                </td>
                <td>
                    @if($vo['is_show'] ==1)
                        <span class="badge badge-success">开启</span>
                    @else
                        <span class="badge badge-danger">关闭</span>
                    @endif
                </td>
                <td>
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/stadium/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
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
                    url:'/admin/stadium/ajax_del',
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