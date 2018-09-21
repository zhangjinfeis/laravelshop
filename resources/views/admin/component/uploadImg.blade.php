{{--
上传单张图片组件-调用方法:
    @include('admin.component.uploadImg',array())
    必填：
    input_name
    width
    height
    可选：
    size kb
    赋值：
    md5
--}}

<style>
.m-uploadimg-one{width:150px;height:150px;overflow:hidden;border:#ddd 1px solid; background:#fff;position: relative;}
.m-uploadimg-one img{ width: 100%; height: auto;}
.m-uploadimg-one span.size{display:inline-block;padding:0 5px;background:rgba(0,0,0,0.5);line-height:18px;height:18px;overflow:hidden;position:absolute;left:5px;top:5px;border-radius:9px;color:#eee;font-size:12px;display:none;}
.m-uploadimg-one span.size-show{ display: inline-block;}
.m-uploadimg-one a.delete{display:inline-block;line-height:20px;height:20px;width:20px;border-radius:10px;text-align:center;overflow:hidden;position:absolute;right:5px;top:5px;text-decoration:none;background: rgba(0,0,0,0.5);font-size:16px;color:#fff; display:none; cursor: pointer;}
.m-uploadimg-one a.delete-show{display:block;}
</style>


<div id="upload-{{$input_name}}">

    <label id="{{$input_name}}_btn" class="u-dmuploader">
        <span role="button" class="btn btn-sm btn-primary" style="width:150px;"><span class="fa fa-upload"></span> 上传图片</span>
        <input type="file" name="files[]" class="hide">
    </label>


    <input type="hidden" data-o="{{$md5 or ''}}" name="{{$input_name}}" value="{{$md5 or ''}}">
    <input type="hidden" name="pic_not_use_id[]" value="">
    <input type="hidden" name="pic_use_id[]" value="">

    <div class="m-uploadimg-one">
        <img id="{{$input_name}}_img" src="{{isset($md5)?'/image/'.$md5:'/resources/admin/images/nopicture.png'}}">
        @if(isset($md5))
        <span class="size size-show">{{$width.'×'.$height}}</span>
        <a class="delete delete-show" data="/resources/admin/images/nopicture.png">×</a>
        @else
        <span class="size"></span>
        <a class="delete" data="/resources/admin/images/nopicture.png">×</a>
        @endif
    </div>

</div>

<script>

    var loading;
    //插件地址：https://github.com/danielm/uploader
    $('#{{$input_name}}_btn').dmUploader({
        url: '/admin/upload/ajax_upload_img',
        dataType: 'json',
        maxFileSize : '{{isset($size)&&$size?$size*1024:(int)ini_get('upload_max_filesize')*1024}}*1024',  //允许上传的大小，单位KB
        allowedTypes: 'image/*',
        multiple:false,
        extraData:{
            _token: $('meta[name="csrf-token"]').attr('content'),
            width : '{{$width or 0}}',
            height : '{{$height or 0}}',
        },
        onComplete: function(){
            //$.danidemo.addLog('#demo-debug', 'default', 'All pending tranfers completed');
            console.log('All pending tranfers completed');
        },
        onUploadProgress: function(id, percent){
            loading = $boot.loading({text:'图片上传中...'});
            //var percentStr = percent + '%';
            //$.danidemo.updateFileProgress(id, percentStr);
        },
        onUploadSuccess: function(id, res){
            loading.close();
            //如果上传失败
            if(res.status == 0){
                $boot.warn({text:res.msg});
            }else {
                //赋值操作
                reset_group{{ $input_name}}(res.data.md5)

                //显示图片
                $("#{{$input_name}}_img").attr('src',res.data.url);
                $("#upload-{{$input_name}}").find('.size').addClass('size-show').text(res.data.width+'×'+res.data.height);
                $("#upload-{{$input_name}}").find('.delete').addClass('delete-show');
            }

        },
        onUploadError: function(id, message){
            console.log(message);
            console.log('Failed to Upload file #' + id + ': ' + message);
        },
        onFileTypeError: function(file){
            console.log('File \'' + file.name + '\' cannot be added: must be an image');
        },
        onFileSizeError: function(file){
            console.log('File \'' + file.name + '\' cannot be added: size excess limit');
        },
        onFileExtError: function(file){
            console.log('File \'' + file.name + '\' has a Not Allowed Extension');
        },
        onFallbackMode: function(message){
            console.log('Browser not supported(do something else here!): ' + message);
        }
    });



//删除当前选中缩略图
$("#upload-{{$input_name}} .m-uploadimg-one a.delete").click(function(){
    var nopicture = $(this).attr('data');
    $("#upload-{{$input_name}} .m-uploadimg-one").find('img').attr({'src':nopicture});
    $("#upload-{{$input_name}}").find('input[name="{{ $input_name }}"]').val('');
    $("#upload-{{$input_name}} .m-uploadimg-one span.size").removeClass('size-show');
    $("#upload-{{$input_name}}").find('.delete').removeClass('delete-show');
});

//上传图片后重新赋值
function reset_group{{ $input_name}}($new_md5){
    //原md5值赋给not_use
    var $old_md5 = $("input[name='{{$input_name}}']").attr('data-o');
    $("#upload-{{$input_name}}").find("input[name='pic_not_use_id[]']").val($old_md5);
    //新md5值赋给use
    $("#upload-{{$input_name}}").find("input[name='pic_use_id[]']").val($new_md5);
    //新md5值赋给input
    $("input[name='{{$input_name}}']").val($new_md5);
}

</script>