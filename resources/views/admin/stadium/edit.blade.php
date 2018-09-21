@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <ol>
            <li>场馆编辑</li>
        </ol>
    </div>
    <div class="h30"></div>


    <form>
        <input name="id" type="hidden" value="{{$article->id}}">
        <div class="form-group">
            <label for="name"><span class="text-danger">* </span>场馆分类</label>
            <select class="form-control" id="cate_id" name="cate_id" style="width:400px;">
                @foreach($cate as $vo)
                    @if(isset($_GET['cate_id']) && $vo['id'] == $_GET['cate_id'])
                        <option selected value="{{$vo['id']}}">{{$vo['depth_name']}}{{$vo['name']}}</option>
                    @else
                        <option value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name"><span class="text-danger">* </span>场馆名称</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="场馆名称" style="width:400px;" value="{{$article->name}}" />
            <small class="form-text text-muted">1-100个字符</small>
        </div>
        <div class="form-group">
            <label>场馆图片</label>
            @include('admin.include.uploadImg',array("input_name"=>"thumb",'input_value'=>$article->thumb_pic))
            <small class="form-text text-muted">尺寸：750*500px</small>
        </div>
        <div class="form-group">
            <label for="address"><span class="text-danger">* </span>地址</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="地址" style="width:400px;" value="{{$article->address}}" />
        </div>
        <div class="form-group">
            <label for="address"><span class="text-danger">* </span>经纬度</label>
            <div>
                @include('admin.component.amapChoose',array(
                'input_lng'=>'lng',
                'input_lat'=>'lat',
                'lng_x'=>$article->lng,
                'lat_y'=>$article->lat,
                'width'=>'800px'
                ))
            </div>
        </div>
        <div class="form-group">
            <label for="phone"><span class="text-danger">* </span>电话</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="电话" style="width:400px;" value="{{$article->phone}}" />
        </div>
        <div class="form-group">
            <label for="price"><span class="text-danger">* </span>价格</label>
            <input type="text" class="form-control" id="price" name="price" placeholder="价格" style="width:400px;" value="{{$article->price}}" />
        </div>
        <div class="form-group">
            <label>场馆介绍</label>
            <div>
                @include('admin.include.ckeditor',array('input_name'=>'body','custom'=>'ckeditor_full','height'=>400,'input_value'=>$article->body))
            </div>
            <small class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label><span class="text-danger">* </span>状态</label>
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
            var data = $('form').serializeObject();
            data.body = editor_body.getData();
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