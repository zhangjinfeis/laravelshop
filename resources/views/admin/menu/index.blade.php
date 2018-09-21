@extends("admin.include.mother")

@section("content")


    <div class="clearfix">
        <div class="float-left">
            <div class="u-breadcrumb">
                <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
                <span class="name">导航设置</span>
            </div>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-primary" onclick="return alert_win('根菜单',0,0);"><i class="fa fa-plus"></i> 添加顶级导航</button>
        </div>
    </div>
    <div class="h15"></div>
    <div id="as" style="display: none;">
        <div class="m-manager-menu-create">

            <div class="form-group">
                <label for="name">父级</label>
                <span class="text-muted pl-2 js-pid"></span>
            </div>
            <input name="parent_id" type="hidden" value="">
            <div class="form-group">
                <label for="name">菜单名称</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="菜单名称">
                <small class="form-text text-muted">1-20个字符</small>
            </div>
            <div class="form-group">
                <label for="url">路径</label>
                <input type="text" class="form-control" id="url" name="url" placeholder="路径">
                <small class="form-text text-muted">路由地址</small>
            </div>
            <div class="form-group">
                <label for="target">打开方式</label>
                <input type="text" class="form-control" id="target" name="target" placeholder="打开方式" value="_self">
                <small class="form-text text-muted">当前窗口：_self，新窗口：_blank</small>
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
            $boot.win({id:'#as',title:'添加导航'});
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
                url:'/admin/menu/ajax_create',
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


    <table class="table table-hover" style="border-bottom:#dee2e6 1px solid;">

            <tr>
                <th width="40">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkbox-all" id="checkbox-0">
                        <label class="custom-control-label"  for="checkbox-0"></label>
                    </div>
                </th>
                <th>ID</th>
                <th>导航名称</th>
                <th>路径</th>
                <th>打开方式</th>
                <th>开启/关闭</th>
                <th>操作</th>
            </tr>

        @foreach($list as $vo)
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{$vo['id']}}" data-id="{{$vo['id']}}">
                        <label class="custom-control-label"  for="checkbox-{{$vo['id']}}"></label>
                    </div>
                </td>
                <td>{{$vo['id']}}</td>
                <td>{!! $vo['depth_name'] !!}{{$vo['name']}}</td>
                <td>{{$vo['url']}}</td>
                <td>{{$vo['target']}}</td>
                <td>
                    @if($vo['is_show'] ==1)
                        <span class="badge badge-success">开启</span>
                    @else
                        <span class="badge badge-danger">关闭</span>
                    @endif

                </td>
                <td>
                    <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/menu/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
                    <a class="btn btn-sm btn-outline-secondary" href="#" role="button" onclick="return del_one({{$vo['id']}});"><i class="fa fa-trash"></i> 删除</a>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            更多
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#"  onclick="return alert_win('{{$vo['name']}}',{{$vo['id']}})"><i class="fa fa-plus"></i> 加子导航</a>
                            <a class="dropdown-item" href="#" onclick="return alert_move_win({{$vo['id']}},'{{$vo['name']}}');"><i class="fa fa-arrows
"></i> 导航移动至</a>

                        </div>
                    </div>
                </td>
            </tr>
        @endforeach

    </table>
    <div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="return ajax_del_all();">删除</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="return ajax_is_show_all();">开启</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="return ajax_un_show_all();">关闭</button>
    </div>

    {{--菜单移动--}}
    <div id="move" class="hide">
        <div>
            <div class="form-group">
                <label for="name">导航名称</label>
                <span class="text-muted pl-2 js-move-name"></span>
            </div>
            <input name="move_id" type="hidden" value="">
            <div class="form-group">
                <label class="js-move-tip">移动到</label>
                <div class="form-inline">
                    <select class="form-control" name="move_to_id" style="width:350px;">
                        @foreach($list as $vo)
                            <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name']}}</option>
                        @endforeach
                    </select>
                    <select class="form-control ml-1" name="move_method" style="width:80px;">
                        <option value="child">内</option>
                        <option value="before">前</option>
                        <option value="after">后</option>
                    </select>
                </div>
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
            //选中select
            $('select[name=move_to_id]').find('option').removeAttr('selected').filter('[value='+id+']').attr('selected',true);
            $boot.win({id:'#move','title':'移动导航'});
            return false;
        }
        //移动菜单提交
        function move_menu(){
            var data = {
                move_id:$('input[name=move_id]').val(),
                move_to_id:$('select[name=move_to_id]').val(),
                move_method:$('select[name=move_method]').val(),
            };
            $.ajax({
                type:'post',
                url:'/admin/menu/ajax_move',
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

        //删除分类all
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
                    url:'/admin/menu/ajax_del',
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

        //开启all
        function ajax_is_show_all(){
            var ids = [];
            $('.checkbox-item').filter(':checked').each(function(){
                ids.push($(this).attr('data-id'));
            });
            if(ids.length < 1){
                $boot.error({text:'请至少选择一个选项'});
                return false;
            }
            $boot.confirm({text:'确认开启所选？'},function(){
                $.ajax({
                    type:'post',
                    url:'/admin/menu/ajax_is_show',
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

        //关闭all
        function ajax_un_show_all(){
            var ids = [];
            $('.checkbox-item').filter(':checked').each(function(){
                ids.push($(this).attr('data-id'));
            });
            if(ids.length < 1){
                $boot.error({text:'请至少选择一个选项'});
                return false;
            }
            $boot.confirm({text:'确认关闭所选？'},function(){
                $.ajax({
                    type:'post',
                    url:'/admin/menu/ajax_un_show',
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

        //删除one
        function del_one(id){
            $boot.confirm({text:'确认删除当前菜单及其子菜单？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/menu/ajax_del',
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