@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
        <span class="name">编辑链接</span>
    </div>
    <div class="h30"></div>


    <form>
        <input name="id" type="hidden" value="{{$article->id}}">
        <div class="form-group">
            <label for="name"><span class="text-danger">* </span>文章分类</label>
            <select class="form-control" id="cate_id" name="cate_id" style="width:400px;">
                @foreach($cate as $vo)
                    @if(isset($article->cate_id) && $vo['id'] == $article->cate_id)
                        <option selected value="{{$vo['id']}}">{{$vo['depth_name']}}{{$vo['name']}}</option>
                    @else
                        <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="title"><span class="text-danger">* </span>标题</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="标题" style="width:400px;" value="{{$article->title or ''}}" />
            <small class="form-text text-muted">1-100个字符</small>
        </div>
        <div class="form-group">
            <label for="url">链接图片</label>
            @include('admin.include.uploadImg',array("input_name"=>"thumb",'input_value'=>$article->thumb_pic))
            <small class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="url">链接</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="链接" style="width:400px;" value="{{$article->url or ''}}" />
        </div>
        <div class="form-group">
            <label for="target"><span class="text-danger">* </span>打开方式</label>
            <input type="text" class="form-control" id="target" name="target" placeholder="打开方式" style="width:400px;" value="{{$article->target or ''}}" />
            <small class="form-text text-muted">_self：本窗口，_blank：新窗口</small>
        </div>

        <div class="form-group">
            <label for="sort">排序</label>
            <input type="text" class="form-control" id="sort" name="sort" placeholder="关键词" style="width:400px;" value="{{$article->sort or ''}}" />
            <small class="form-text text-muted">默认50，越小排序越靠前</small>
        </div>

        <div class="form-group">
            <label>状态</label>
            <div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="is_show1" name="is_show" class="custom-control-input" value="1"
                    @if($article->is_show == 1)
                        checked
                    @endif
                    >
                    <label class="custom-control-label" for="is_show1">开启</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="is_show2" name="is_show" class="custom-control-input" value="9"
                    @if($article->is_show == 9)
                       checked
                    @endif
                    >
                    <label class="custom-control-label" for="is_show2">关闭</label>
                </div>
            </div>
        </div>

        <div class="h10"></div>
        <button type="submit" class="btn btn-primary" onclick="return post_edit();">保存</button>
    </form>
    <script>
        //提交编辑
        function post_edit(){
            var data = $('form').serialize();
            $.ajax({
                type:'post',
                url:'/admin/stadium/edit',
                data:data,
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