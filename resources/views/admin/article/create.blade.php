@extends("admin.include.mother")

@section("content")
    <div class="u-breadcrumb">
        <a class="back" href="{{ url()->previous() }}" ><span class="fa fa-chevron-left"></span> 后退</a>
        <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
        <span class="name">新增文章</span>
    </div>
    <div class="h30"></div>


    <div class="nav nav-tabs" role="tablist">
        <a class="nav-item nav-link active" data-toggle="tab" href="#nav-1" role="tab" aria-selected="true">基本信息</a>
        <a class="nav-item nav-link" data-toggle="tab" href="#nav-2" role="tab" aria-selected="false">附加值</a>
        <a class="nav-item nav-link" data-toggle="tab" href="#nav-3" role="tab" aria-selected="false">文章内容</a>
        <a class="nav-item nav-link" data-toggle="tab" href="#nav-4" role="tab" aria-selected="false">SEO</a>
    </div>
    <div class="h15"></div>


    <form>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="nav-1" role="tabpanel">
                <div class="form-group">
                    <label><span class="text-danger">* </span>文章分类</label>
                    <select class="form-control w400" name="cate_id">
                        @foreach($cate as $vo)
                            <option @if($vo['id'] == request('cate_id')) selected @endif @if($vo['is_able'] == 9) disabled @endif value="{{$vo['id']}}">{!! $vo['depth_name'] !!}{{$vo['name_cn']}}({{$vo['count']}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title"><span class="text-danger">* </span>标题</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="标题" style="width:400px;" value="" />
                    <small class="form-text text-muted">1-100个字符</small>
                </div>
                <div class="form-group">
                    <label for="title_sub">副标题</label>
                    <input type="text" class="form-control" id="title_sub" name="title_sub" placeholder="副标题" style="width:400px;" value="" />
                    <small class="form-text text-muted">1-100个字符</small>
                </div>
                <div class="form-group">
                    <label for="url">链接</label>
                    <input type="text" class="form-control" id="url" name="url" placeholder="链接" style="width:400px;" value="" />
                    <small class="form-text text-muted">当填写外链时，文章内容将不显示</small>
                </div>
                <div class="form-group">
                    <label for="url">缩略图</label>
                    @include('admin.component.upload_img',array("input_id"=>md5("thumb"),"input_name"=>"thumb"))
                    <small class="form-text text-muted"></small>
                </div>

                <div class="form-group">
                    <label for="sort">排序</label>
                    <input type="text" class="form-control" id="sort" name="sort" placeholder="排序" style="width:400px;" value="" />
                    <small class="form-text text-muted">默认50，越小排序越靠前</small>
                </div>

                <div class="form-group">
                    <label for="sort">日期</label>
                    <div class="input-group" style="width: 400px;">
                        <input type="text" class="form-control" placeholder="选择时间" id="datetime">
                        <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </span>
                        </div>
                    </div>
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
            </div>
            <div class="tab-pane fade show" id="nav-2" role="tabpanel">
                <div class="js-exattr-container"></div>
            </div>
            <div class="tab-pane fade show" id="nav-3" role="tabpanel">
                <div class="form-group">
                    <label for="url">内容</label>
                    <div>
                        @include('admin.component.ckeditor',array('input_id'=>'body','input_name'=>'body','custom'=>'full','height'=>400))
                    </div>

                    <small class="form-text text-muted"></small>
                </div>
            </div>
            <div class="tab-pane fade show" id="nav-4" role="tabpanel">
                <div class="form-group">
                    <label for="keywords">关键词</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" placeholder="关键词" style="width:400px;" value="" />
                    <small class="form-text text-muted">不填则使用系统默认关键词</small>
                </div>

                <div class="form-group">
                    <label for="description">描述</label>

                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="描述"></textarea>
                    <small class="form-text text-muted">不填则使用系统默认描述</small>
                </div>
            </div>
        </div>



        <div class="h10"></div>
        <button type="submit" class="btn btn-primary" onclick="return post_create();">保存</button>
    </form>
    <script>
        //提交编辑
        function post_create(){
            var data = $('form').serializeObject();
            //data.body = editor_body.getData();

            $.ajax({
                type:'post',
                url:'/admin/article/create',
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

        //文章发布日期
        $("#datetime").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            locale: "zh"
        });


        //加载附加值
        function load_exattr(cate_id,article_id){
            $.ajax({
                type:'post',
                url:'/admin/article/ajax_exattr',
                data:{cate_id:cate_id,article_id:article_id},
                success:function(res){
                    if(res.status == 1){
                        $('.js-exattr-container').html(res.html);
                    }
                }
            })
        }
        load_exattr($('select[name=cate_id]').val());
        $('select[name=cate_id]').change(function(){
            load_exattr($(this).val());
        });

    </script>

@endsection