@extends("admin.include.mother")
@section('content')


    <div class="clearfix">
        <div class="float-left">
            <div class="u-breadcrumb">
                <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
                <span class="name">参数</span>
            </div>
        </div>
        <div class="float-right">
            <a role="button" class="btn btn-sm btn-primary" href="#" onclick="return alert_win();"><i class="fa fa-plus"></i> 新增参数</a>
        </div>
    </div>
    <div class="h15"></div>


    <div id="conf-add" style="display: none;">
        <form id="form1">
        <div class="m-manager-menu-create">
            <div class="form-group">
                <label><span class="text-danger">* </span>参数分类</label>
                <select class="form-control" name="cate_id" lay-filter="type">
                    @foreach($cate as $vo)
                        <option value="{{$vo->id}}">{{$vo->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label><span class="text-danger">* </span>参数名称</label>
                <input type="text" class="form-control" name="name" placeholder="参数名称">
                <small class="form-text text-muted">1-20个字符</small>
            </div>
            <div class="form-group">
                <label><span class="text-danger">* </span>组件类型</label>
                <select class="form-control" name="type" lay-filter="type">
                    @foreach(config('config.config_type') as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="key"><span class="text-danger">* </span>键</label>
                <input type="text" class="form-control" id="key" name="key" placeholder="键" value="">
                <small class="form-text text-muted">取值的字段名</small>
            </div>
            <div class="form-group js-value">
                <label>值</label>
                <input type="text" class="form-control" name="value" placeholder="值" value="">
            </div>
            <div class="form-group hide js-width">
                <label>宽度</label>
                <input type="text" class="form-control" name="width" placeholder="宽度" value="">
                <small class="form-text text-muted">图片或编辑器的宽度，0或空值表示不限制</small>
            </div>
            <div class="form-group hide js-height">
                <label>高度</label>
                <input type="text" class="form-control" name="height" placeholder="高度" value="">
                <small class="form-text text-muted">图片或编辑器的高度，0或空值表示不限制</small>
            </div>
            <div class="form-group hide js-size">
                <label>图片允许大小</label>
                <input type="text" class="form-control" name="size" placeholder="图片允许大小" value="">
                <small class="form-text text-muted">单位：M，0或空值表示不限制</small>
            </div>
            <div class="form-group hide js-custom">
                <label>编辑器类型</label>
                <select class="form-control" name="custom">
                    @foreach(config('config.ckeditor_custom') as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted"></small>
            </div>
            <div class="form-group">
                <label for="tips">小贴士</label>
                <input type="text" class="form-control" id="tips" name="tips" placeholder="小贴士" value="">
                <small class="form-text text-muted">字段的说明</small>
            </div>
            <div class="form-group">
                <label for="sort"><span class="text-danger">* </span>排序</label>
                <input type="text" class="form-control" id="sort" name="sort" placeholder="排序" value="500">
                <small class="form-text text-muted">默认500,数值越小排名越靠前</small>
            </div>
            <div class="h10"></div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="return create_config();">新增</button>
            </div>
        </div>
        </form>
    </div>
    <script>
        //弹出创建菜单窗口
        function alert_win(){
            $boot.win({id:'#conf-add','title':'新增参数'});
            return false;
        }

        //类型切换
        $('select[name=type]').change(function(){
            if($(this).val() == 3 || $(this).val() == 4){
                $('.js-value').hide().find('input[name=value]').val('');
                $('.js-custom').hide();
                $('.js-width').show();
                $('.js-height').show();
                $('.js-size').show();
            }else if($(this).val() == 5){
                $('.js-value').hide().find('input[name=value]').val('');
                $('.js-custom').show();
                $('.js-width').show();
                $('.js-height').show();
                $('.js-size').hide();
            }else{
                $('.js-value').show();
                $('.js-custom').hide();
                $('.js-width').hide().find('input[name=width]').val('');
                $('.js-height').hide().find('input[name=height]').val('');
                $('.js-size').hide().find('input[name=size]').val('');
            }
        });

        //新增菜单提交
        function create_config(){
            if(!$('input[name=name]').val()){
                $boot.warn({text:'分类名称不能为空'});
                return false;
            }
            var data = $('#form1').serialize();
            $.ajax({
                type:'post',
                url:'/admin/config/ajax_create',
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


    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($cate as $vo)
            <li class="nav-item">
                <a class="nav-link @if($vo->id == $cate_id) active @endif" href="{{url('admin/config').'?cate_id='.$vo->id}}" role="tab">{{$vo->name}}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="h20"></div>
            <form id="form2">
                @foreach($conf as $vo)
                    <div class="form-group">
                        <label>{{$vo->name}}<span class="text-muted">（键:{{$vo->key}},排序:{{$vo->sort}}）</span></label>
                        @switch($vo->type)
                            @case(1)
                                <input type="text" class="form-control" id="{{$vo->key}}" name="{{$vo->key}}" placeholder="{{$vo->name}}" style="width:400px;" value="{{$vo->value}}" />
                            @break
                            @case(2)
                                <textarea class="form-control" id="{{$vo->key}}" rows="3" name="{{$vo->key}}">{{$vo->value}}</textarea>
                            @break
                            @case(3)
                            @include('admin.component.upload_img',array("input_id"=>md5($vo->key),"input_name"=>$vo->key,'width'=>$vo->width,'height'=>$vo->height,'input_value'=>$vo->value,'size'=>$vo->size))
                            @break
                            @case(4)
                            @include('admin.component.upload_imgs',array("input_id"=>md5($vo->key),"input_name"=>$vo->key,'width'=>$vo->width,'height'=>$vo->height,'input_value'=>$vo->value,'size'=>$vo->size))
                            @break
                            @case(5)
                            @include('admin.component.ckeditor',array("input_id"=>md5($vo->key),"input_name"=>$vo->key,'width'=>$vo->width,'height'=>$vo->height,'input_value'=>$vo->value,'custom'=>$vo->custom))
                            @break
                        @endswitch
                        <small class="form-text text-muted">
                            @if($vo->tips)
                                {{$vo->tips}}&nbsp;&nbsp;&nbsp;
                            @endif
                            @if($vo->is_system == 0)
                            <a href="{{url('admin/config/edit').'?id='.$vo->id}}" class="text-primary">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="text-primary" onclick="return del_config({{$vo->id}})">删除</a></small>
                            @else
                            <span class="text-success">系统字段</span>
                            @endif
                    </div>
                @endforeach
            </form>

        </div>
    </div>
    <div class="h10"></div>
    <button type="submit" class="btn btn-primary" onclick="return post_edit();">保存</button>

    <script>


        //删除
        function del_config(id){
            $boot.confirm({text:'确认删除当前参数？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/config/ajax_del',
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

        //新增菜单提交
        function post_edit(){
            var data = $('#form2').serialize();
            $.ajax({
                type:'post',
                url:'/admin/config/ajax_edit_value',
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

@endsection