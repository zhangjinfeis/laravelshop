@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <span class="name">编辑参数</span>
    </div>
    <div class="h30"></div>


    <form>
        <input type="hidden" name="id" value="{{$conf->id}}" />
        <div class="form-group">
            <label for="cate_id"><span class="text-danger">* </span>参数分类</label>
            <select class="form-control" id="cate_id" name="cate_id" lay-filter="type" style="width:400px;">
                @foreach($cate as $vo)
                    <option value="{{$vo->id}}" @if($vo->id == $conf->cate_id) selected @endif>{{$vo->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name"><span class="text-danger">* </span>参数名称</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="参数名称" style="width:400px;" value="{{$conf->name or ''}}">
            <small class="form-text text-muted">1-20个字符</small>
        </div>
        <div class="form-group">
            <label for="type"><span class="text-danger">* </span>组件类型</label>
            <select class="form-control" id="type" name="type" lay-filter="type" style="width:400px;">
                @foreach(config('config.config_type') as $k=>$v)
                    @if($k == $conf->type)
                        <option value="{{$k}}" selected>{{$v}}</option>
                    @else
                        <option value="{{$k}}">{{$v}}</option>
                    @endif

                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="key"><span class="text-danger">* </span>键</label>
            <input type="text" class="form-control" id="key" name="key" placeholder="键" style="width:400px;" value="{{$conf->key or ''}}">
            <small class="form-text text-muted">取值的字段名</small>
        </div>
        <div class="form-group @if(!in_array($conf->type,[3,4,5])) hide @endif js-width">
            <label>宽度</label>
            <input type="text" class="form-control" name="width" placeholder="宽度" style="width:400px;" value="{{$conf->width or ''}}">
            <small class="form-text text-muted">图片的宽度，0或空值表示不限制</small>
        </div>
        <div class="form-group @if(!in_array($conf->type,[3,4,5])) hide @endif js-height">
            <label>高度</label>
            <input type="text" class="form-control" name="height" placeholder="高度" style="width:400px;" value="{{$conf->height or ''}}">
            <small class="form-text text-muted">图片的高度，0或空值表示不限制</small>
        </div>
        <div class="form-group @if(!in_array($conf->type,[3,4])) hide @endif js-size">
            <label>图片允许大小</label>
            <input type="text" class="form-control" name="size" placeholder="图片允许大小" style="width:400px;" value="{{$conf->size or ''}}">
            <small class="form-text text-muted">单位：M，0或空值表示不限制</small>
        </div>
        <div class="form-group @if(!in_array($conf->type,[5])) hide @endif js-custom">
            <label>编辑器类型</label>
            <select class="form-control w400" name="custom">
                @foreach(config('config.ckeditor_custom') as $k=>$v)
                    <option value="{{$k}}" @if($k == $conf->custom) selected @endif>{{$v}}</option>
                @endforeach
            </select>
            <small class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="tips">小贴士</label>
            <input type="text" class="form-control" id="tips" name="tips" placeholder="小贴士" style="width:400px;" value="{{$conf->tips or ''}}">
            <small class="form-text text-muted">字段的说明</small>
        </div>
        <div class="form-group">
            <label for="sort"><span class="text-danger">* </span>排序</label>
            <input type="text" class="form-control" id="sort" name="sort" placeholder="排序" style="width:400px;" value="{{$conf->sort or ''}}">
            <small class="form-text text-muted">默认500,数值越小排名越靠前</small>
        </div>
        <div class="h10"></div>
        <button type="submit" class="btn btn-primary" onclick="return post_edit();">保存</button>
    </form>
    <script>
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
        //提交编辑
        function post_edit(){
            $.ajax({
                type:'post',
                url:'/admin/config/edit',
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