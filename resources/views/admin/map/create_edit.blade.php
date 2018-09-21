@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        @if(isset($page->id))
            <span class="name">编辑地图</span>
        @else
            <span class="name">新增地图</span>
        @endif
    </div>
    <div class="h30"></div>


    <form>
        @if(isset($page->id))
            <input type="hidden" name="id" value="{{$page->id}}" />
        @endif
        <div class="form-group">
            <label for="title"><span class="text-danger">* </span>地图标题</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="地图标题" style="width:400px;" value="{{$page->title or ''}}" />
            <small class="form-text text-muted">1-100个字符</small>
        </div>

        <div class="form-group">
            <label><span class="text-danger">* </span>地址选择</label>
            <div>
                @include('admin.component.amap_choose',array('input_lng'=>'lng','input_lat'=>'lat','input_zoom'=>'zoom','lng'=>$page->lng??'','lat'=>$page->lat??'','zoom'=>$page->zoom??'','height'=>'400px','width'=>'600px'))
            </div>
            <small class="form-text text-muted"></small>
        </div>

        <div class="form-group">
            <label>描述</label>
            <div>
                @include('admin.component.ckeditor',array('input_id'=>'description','input_name'=>'description','custom'=>'ckeditor_text_link','height'=>300,'width'=>600,'input_value'=>$page->description ?? '','custom'=>'text_link'))
            </div>
            <small class="form-text text-muted">地图说明，如交通路线</small>
        </div>

        <div class="form-group">
            <label>地址</label>
            <input type="text" class="form-control" name="address" placeholder="地址" style="width:400px;" value="{{$page->address or ''}}" />
            <small class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label>电话</label>
            <input type="text" class="form-control" name="phone" placeholder="电话" style="width:400px;" value="{{$page->phone or ''}}" />
            <small class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label>邮箱</label>
            <input type="text" class="form-control" name="email" placeholder="邮箱" style="width:400px;" value="{{$page->email or ''}}" />
            <small class="form-text text-muted"></small>
        </div>

        <div class="form-group">
            <label>QQ</label>
            <input type="text" class="form-control" name="qq" placeholder="QQ" style="width:400px;" value="{{$page->qq or ''}}" />
            <small class="form-text text-muted"></small>
        </div>

        <div class="h10"></div>
        <button type="submit" class="btn btn-primary" onclick="return post_create();">保存</button>
    </form>
    <script>
        //提交编辑
        function post_create(){
            var data = $('form').serializeObject();
            data.description = editor_description.getData();
            $.ajax({
                type:'post',
                url:'/admin/map/create_edit',
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