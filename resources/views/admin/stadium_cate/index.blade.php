@extends("admin.include.mother")

@section("content")

    <div class="u-breadcrumb">
        <ol>
            <li>公共模块</li>
            <li>链接</li>
            <li>链接分类</li>
        </ol>
    </div>
    <div class="h20"></div>

    <button type="button" class="btn btn-sm btn-primary" onclick="return alert_win('根菜单',0,0);"><i class="fa fa-plus"></i> 新增顶级分类</button>


    <div class="h15"></div>
    <div id="as" style="display: none;">
        <div class="m-manager-menu-create">

            <div class="form-group">
                <label for="name">父级</label>
                <span class="text-muted pl-2 js-pid"></span>
            </div>
            <input name="parent_id" type="hidden" value="">
            <div class="form-group">
                <label for="name"><span class="text-danger">* </span>分类名称</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="菜单名称">
                <small class="form-text text-muted">1-20个字符</small>
            </div>
            <div class="form-group">
                <label>状态</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="is_show1" name="is_show" class="custom-control-input" value="1" checked>
                        <label class="custom-control-label" for="is_show1">开启</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="is_show2" name="is_show" class="custom-control-input" value="9">
                        <label class="custom-control-label" for="is_show2">关闭</label>
                    </div>
                </div>
            </div>
            <div class="h10"></div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="return create_menu();">新增</button>
            </div>
        </div>
    </div>

    <script>
        //弹出创建菜单窗口
        function alert_win(name,id){
            $('.js-pid').text(name);
            $('input[name=parent_id]').val(id);
            $boot.win({id:'#as','title':'新增分类'});
            return false;
        }


        //新增菜单提交
        function create_menu(){
            if(!$('input[name=name]').val()){
                $boot.warn({text:'菜单名称不能为空'});
                return false;
            }
            var data = {
                parent_id:$('input[name=parent_id]').val(),
                name:$('input[name=name]').val(),
                url:$('input[name=url]').val(),
                target:$('input[name=target]').val(),
                is_show:$('input[name=is_show]').filter(':checked').val()
            };
            $.ajax({
                type:'post',
                url:'/admin/stadium_cate/ajax_create',
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
        <thead>
            <tr>
                <th>ID</th>
                <th>分类名称/英文名称</th>
                <th>文章数</th>
                <th>开启/关闭</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach($list as $vo)
            <tr>
                <td>{{$vo['id']}}</td>
                <td>
                    <a href="{{url('admin/link').'?cate_id='.$vo['id']}}">
                        {!! $vo['depth_name'] !!}{{$vo['name']}}
                    </a>

                </td>
                <td>
                    {{$vo['total']}}
                </td>
                <td>
                    @if($vo['is_show'] ==1)
                        <span class="badge badge-success">开启</span>
                    @else
                        <span class="badge badge-danger">关闭</span>
                    @endif

                </td>
                <td>
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/stadium_cate/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
                    {{--<a class="btn btn-sm btn-outline-secondary" href="#" role="button" onclick="return alert_win('{{$vo['name']}}',{{$vo['id']}})"><i class="fa fa-plus"></i> 加子分类</a>--}}
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            更多
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="return del_menu({{$vo['id']}});"><i class="fa fa-trash"></i> 删除</a>
                            {{--<a class="dropdown-item" href="#" onclick="return alert_move_win({{$vo['id']}},'{{$vo['name']}}','child');"><i class="fa fa-arrows
"></i> 移到菜单下</a>--}}
                            <a class="dropdown-item" href="#" onclick="return alert_move_win({{$vo['id']}},'{{$vo['name']}}','before');"><i class="fa fa-arrows
"></i> 移到菜单前</a>
                            <a class="dropdown-item" href="#" onclick="return alert_move_win({{$vo['id']}},'{{$vo['name']}}','after');"><i class="fa fa-arrows
"></i> 移到菜单后</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div id="move" class="hide">
        <div>
            <div class="form-group">
                <label for="name">菜单名称</label>
                <span class="text-muted pl-2 js-move-name"></span>
            </div>
            <input name="move_id" type="hidden" value="">
            <input name="move_method" type="hidden" value="">
            <div class="form-group">
                <label for="power_id" class="js-move-tip">移动到以下菜单下</label>
                <select class="form-control" id="power_id" name="move_to_id">
                    @foreach($list as $vo)
                        <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="h10"></div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="return move_menu();">移动</button>
            </div>
        </div>
    </div>

    <script>
        //弹出移动菜单窗口
        function alert_move_win(id,name,method){
            $('.js-move-name').text(name);
            $('input[name=move_id]').val(id);
            $('input[name=move_method]').val(method);
            switch(method){
                case 'child':$('.js-move-tip').text('移动到以下分类下');break;
                case 'before':$('.js-move-tip').text('移动到以下分类前');break;
                case 'after':$('.js-move-tip').text('移动到以下分类后');break;
            }
            //选中select
            $('select[name=move_to_id]').find('option').removeAttr('selected').filter('[value='+id+']').attr('selected',true);
            $boot.win({id:'#move','title':'移动分类'});
            return false;
        }
        //移动菜单提交
        function move_menu(){
            var data = {
                move_id:$('input[name=move_id]').val(),
                move_to_id:$('select[name=move_to_id]').val(),
                move_method:$('input[name=move_method]').val(),
            };
            $.ajax({
                type:'post',
                url:'/admin/stadium_cate/ajax_move',
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

        //删除
        function del_menu(id){
            $boot.confirm({text:'确认删除当前分类及其子分类？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/stadium_cate/ajax_del',
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