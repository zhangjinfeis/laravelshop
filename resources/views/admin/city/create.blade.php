@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <ol>
            <li>公共模块</li>
            <li>链接</li>
            <li><a href="{{url('admin/link')}}">链接列表</a></li>
            <li>新增</li>
        </ol>
    </div>
    <div class="h30"></div>


    <form>
        <div class="form-group">
            <label for="name"><span class="text-danger">* </span>城市名称</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="城市名称" style="width:400px;" value="" />
            <small class="form-text text-muted">1-50个字符</small>
        </div>
        <div class="form-group">
            <label for="sort"><span class="text-danger">* </span>排序</label>
            <input type="text" class="form-control" id="sort" name="sort" placeholder="排序" style="width:400px;" value="50" />
            <small class="form-text text-muted">默认50，越小排序越靠前</small>
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
        <button type="submit" class="btn btn-primary" onclick="return post_create();">保存</button>
    </form>
    <script>
        //提交编辑
        function post_create(){
            var data = $('form').serialize();
            $.ajax({
                type:'post',
                url:'/admin/city/create',
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