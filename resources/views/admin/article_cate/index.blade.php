@extends("admin.include.mother")

@section("content")
    <div class="clearfix">
        <div class="float-left">
            <div class="u-breadcrumb">
                <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
                <span class="name">文章分类</span>
            </div>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-primary" onclick="return alert_win('根菜单',0,0);"><i class="fa fa-plus"></i> 新增顶级分类</button>
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
                <label for="name_cn"><span class="text-danger">* </span>分类名称</label>
                <input type="text" class="form-control" id="name_cn" name="name_cn" placeholder="分类名称">
                <small class="form-text text-muted">1-20个字符</small>
            </div>
            <div class="form-group">
                <label for="name_en">英文名称</label>
                <input type="text" class="form-control" id="name_en" name="name_en" placeholder="英文名称">
                <small class="form-text text-muted">1-20个字符，选填</small>
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
            if(!$('input[name=name_cn]').val()){
                $boot.warn({text:'菜单名称不能为空'});
                return false;
            }
            var data = {
                parent_id:$('input[name=parent_id]').val(),
                name_cn:$('input[name=name_cn]').val(),
                name_en:$('input[name=name_en]').val(),
                is_show:$('input[name=is_show]').filter(':checked').val()
            };
            $.ajax({
                type:'post',
                url:'/admin/article_cate/ajax_create',
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
            <th>分类名称/英文名称</th>
            <th>开启/关闭</th>
            <th>操作</th>
        </tr>

    @foreach($list as $vo)
        <tr>
            <td>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{$vo['id']}}" data-id="{{$vo['id']}}" data-count="{{$vo['count']}}">
                    <label class="custom-control-label"  for="checkbox-{{$vo['id']}}"></label>
                </div>
            </td>
            <td>{{$vo['id']}}</td>
            <td>
                <a href="{{url('admin/article').'?cate_id='.$vo['id']}}">
                    {!! $vo['depth_name'] !!}{{$vo['name_cn']}}
                    @if($vo['name_en'])
                        /{{$vo['name_en']}}
                    @endif
                    @if($vo['is_able'] == 9)
                        <span style="text-decoration:line-through;">({{$vo['count']}})</span>
                    @else
                        ({{$vo['count']}})
                    @endif

                </a>

            </td>
            <td>
                @if($vo['is_show'] ==1)
                    <span class="badge badge-success">开启</span>
                @else
                    <span class="badge badge-danger">关闭</span>
                @endif

            </td>
            <td>
                <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/article_cate/edit?id='.$vo['id'])}}" role="button"><i class="fa fa-edit"></i> 编辑</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{url('/admin/article_cate/edit?id='.$vo['id'])}}" role="button" onclick="return del_one({{$vo['id']}},{{$vo['count']}});"><i class="fa fa-trash"></i> 删除</a>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        更多
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="return alert_win('{{$vo['name_cn']}}',{{$vo['id']}})"><i class="fa fa-plus"></i> 子分类</a>
                        <a class="dropdown-item" href="#" onclick="return alert_move_win({{$vo['id']}},'{{$vo['name_cn']}}');"><i class="fa fa-arrows
"></i> 分类移到至</a>
                        <a class="dropdown-item" href="#" onclick="return alert_move_content_win({{$vo['id']}},'{{$vo['name_cn']}}');"><i class="fa fa-arrows"></i> 文章移动至</a>
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

    <div id="win1" class="hide">
        <div id="move">
            <div class="form-group">
                <label for="name">分类名称</label>
                <span class="text-muted pl-2 js-move-name"></span>
            </div>
            <input name="move_id" type="hidden" value="">
            <div class="form-group">
                <label class="js-move-tip">移动到</label>
                <div class="form-inline">
                    <select class="form-control" name="move_to_id" style="width:350px;">
                        @foreach($list as $vo)
                            <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name_cn']}}</option>
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

    <div id="win2" class="hide">
        <div id="move_content">
            <div class="form-group">
                <label for="name">分类名称</label>
                <span class="text-muted pl-2 js-move-name"></span>
            </div>
            <input name="move_id" type="hidden" value="">
            <div class="form-group">
                <label class="js-move-tip">文章移动到</label>
                <select class="form-control" name="move_to_id">
                    @foreach($list as $vo)
                        <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name_cn']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="h10"></div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="return move_content();">移动</button>
            </div>
        </div>
    </div>

    <script>
        //弹出移动分类窗口
        function alert_move_win(id,name){
            $('#move .js-move-name').text(name);
            $('#move input[name=move_id]').val(id);
            //选中select
            $('#move select[name=move_to_id]').find('option').removeAttr('selected').filter('[value='+id+']').attr('selected',true);
            $boot.win({id:'#win1','title':'移动分类'});
            return false;
        }
        //移动分类提交
        function move_menu(){
            var data = {
                move_id:$('#move input[name=move_id]').val(),
                move_to_id:$('#move select[name=move_to_id]').val(),
                move_method:$('#move select[name=move_method]').val(),
            };
            $.ajax({
                type:'post',
                url:'/admin/article_cate/ajax_move',
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


        //弹出移动分类下文章窗口
        function alert_move_content_win(id,name){
            $('#move_content .js-move-name').text(name);
            $('#move_content input[name=move_id]').val(id);
            //选中select
            $('#move_content select[name=move_to_id]').find('option').removeAttr('selected').filter('[value='+id+']').attr('selected',true);
            $boot.win({id:'#win2','title':'移动文章'});
            return false;
        }
        //移动分类下分类提交
        function move_content(){
            var data = {
                move_id:$('#move_content input[name=move_id]').val(),
                move_to_id:$('#move_content select[name=move_to_id]').val(),
            };
            $.ajax({
                type:'post',
                url:'/admin/article_cate/ajax_move_content',
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
            var ids = [],content_count = 0;
            $('.checkbox-item').filter(':checked').each(function(){
                ids.push($(this).attr('data-id'));
                content_count += $(this).attr('data-count');
            });
            if(ids.length < 1){
                $boot.error({text:'请至少选择一个选项'});
                return false;
            }
            if(content_count > 0){
                $boot.error({text:'请先删除选取分类下的文章'});
                return false;
            }
            $boot.confirm({text:'确认删除所选？'},function(){
                $.ajax({
                    type:'post',
                    url:'/admin/article_cate/ajax_del',
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
                    url:'/admin/article_cate/ajax_is_show',
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
                    url:'/admin/article_cate/ajax_un_show',
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

        //删除
        function del_one(id,content_count){
            if(content_count > 0){
                $boot.error({text:'请先删除当前分类下的文章'});
                return false;
            }
            $boot.confirm({text:'将删除当前分类及子分类？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/article_cate/ajax_del',
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