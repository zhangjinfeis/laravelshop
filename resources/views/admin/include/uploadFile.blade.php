{{--
上传单文件组件

需要传递的参数：
    @include('admin.component.uploadFile',[
        "input_name" => "文件对应的表单字段名", (必填)
        "input_value" => "文件的md5", (必填)
        ,"size"=>'3' 单位KB
        ,"upload_url"=>'/admin/file/ajax_uploadFile'//上传地址
        ,"button_name" => '按钮名称'
    ])
--}}

<style>
    .m-uploadfile-one{width:200px; height: 50px;border:#ddd 1px solid;padding:5px; display: none; position: relative;}
    .m-uploadfile-one-show{display:block;}
    .m-uploadfile-one img{ float:left;width:50px;height:50px;}
    .m-uploadfile-one .title{ float: left;padding:0 0 0 5px;height:50px; line-height: 50px; overflow: hidden;}
    .m-uploadfile-one a.delete{display:inline-block;line-height:20px;height:20px;width:20px;border-radius:10px;text-align:center;overflow:hidden;position:absolute;right:5px;top:5px;text-decoration:none;background: rgba(0,0,0,0.5);font-size:16px;color:#fff;cursor: pointer;}
</style>
<div id="upload-{{$input_name}}">
    <button type="button" class="layui-btn" id="upload-btn-{{$input_name}}"><i class="layui-icon"></i>{{$button_name??"上传文件"}}</button>
    <input type="hidden" data-o="{{$input_value->md5 or ''}}" name="{{$input_name}}" value="{{isset($input_value->md5)?$input_value->md5:''}}" id="file_{{$input_name}}">
    <input type="hidden" name="file_old[]">
    <input type="hidden" name="file_new[]">
    <div class="layui-upload-list">
        <div class="m-uploadfile-one {{isset($input_value)?'m-uploadfile-one-show':''}}">
            <img class="layui-upload-img js-fileimg" src="{{isset($input_value->ext)?'/admin/images/icon_'.$input_value->ext.'.png':'#'}}">
            <span class="title js-filename">{{$input_value->prename or ''}}</span>
            <a class="delete">×</a>
        </div>
    </div>
</div>


<script>
layui.use('upload', function(){
    var $ = layui.jquery
        ,upload = layui.upload;
    var index;
    //指定允许上传的文件类型
    var uploadInst = upload.render({
        elem: '#upload-btn-{{$input_name}}'
        ,url: '{{$upload_url??"/admin/upload/ajax_upload_file"}}'
        ,size:{{isset($size)?$size:(int)ini_get('upload_max_filesize')*1024}}*1024   //允许上传的大小，单位KB
        ,accept: 'file' //普通文件
        ,exts: 'png|jpg|jpeg|gif|bmp|flv|swf|mkv|avi|rm|rmvb|mpeg|mpg|ogg|ogv|mov|wmv|mp4|webm|mp3|wav|mid|rar|zip|tar|gz|7z|bz2|cab|iso|doc|docx|xls|xlsx|ppt|pptx|pdf|txt|md|xml' //只允许上传压缩文件
        ,before: function(){
            loading = layer.load();
        }
        ,done: function(res){
            if(res.status){
                var old = $('input[name={{$input_name}}]').attr('data-o');
                //将老的MD5存入对应表单
                if(old){
                    $('#upload-{{$input_name}} input[name="file_old[]"]').val(old);
                }
                //将新的MD5存入对应表单
                $('#upload-{{$input_name}} input[name="{{$input_name}}"]').val(res.data.md5);
                $('#upload-{{$input_name}} input[name="file_new[]"]').val(res.data.md5);

                //显示对应文件类型icon
                $('#upload-{{$input_name}} .m-uploadfile-one').addClass('m-uploadfile-one-show');
                $('#upload-{{$input_name}} .js-fileimg').attr('src','/admin/images/icon_'+res.data.icon+'.jpg');
                $('#upload-{{$input_name}} .js-filename').text(res.data.filename);
                layer.msg(res.msg,{icon:6,time:1000});
            }else{
                layer.msg(res.msg,{icon:5,time:1000});
            }
            layer.close(loading);
        }
        ,error: function(){
            layer.close(loading);
            layer.msg('上传失败~',{icon:5,time:1000});
        }
    });
});

//删除当前选中的图片
$("#upload-{{$input_name}} a.delete").click(function(){
    $('input[name="{{ $input_name }}"]').val('');
    $('#upload-{{$input_name}} input[name="file_new[]"]').val('');
    $('#upload-{{$input_name}} .m-uploadfile-one').removeClass('m-uploadfile-one-show');
});


</script>