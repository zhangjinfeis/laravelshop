

{{--上传多张图片组件
@include('uploadImgs',array());
需要传递的参数：array(
        "input_name"=>"多图片对应的表单字段",(必填、唯一)
        "input_value"=>"多图片的二维数组值"，(必填)
        "size" => "" 单位KB
    }--}}

<style>
.m-uploadimg-group{ background:#f2f2f2;overflow: hidden;}
.m-uploadimg-group ul{float:left;margin: 0px;padding:0px;}
.m-uploadimg-group ul li{float:left;width:150px;height:150px;margin:10px 0px 10px 10px;position:relative;border:#ddd 1px solid; background:#fff;overflow: hidden;}
.m-uploadimg-group ul li img{ width: 100%;}
.m-uploadimg-group ul li.default{display:none;}
.m-uploadimg-group ul li span.size{position:absolute;left:5px;top:5px;display:inline-block;height:18px;line-height:18px;border-radius:9px;padding:0 5px;background:rgba(0,0,0,0.5);color:#fff; font-size: 12px;}
.m-uploadimg-group ul li a.prev{position:absolute;left:50px;bottom:5px;display:inline-block;height:20px;line-height:18px;width:20px;border-radius:10px;text-align:center;background:rgba(0,0,0,0.5);color:#fff; cursor: pointer;}
.m-uploadimg-group ul li a.next{position:absolute;left:80px;bottom:5px;display:inline-block;height:20px;line-height:18px;width:20px;border-radius:10px;text-align:center;background:rgba(0,0,0,0.5);color:#fff;cursor: pointer;}
.m-uploadimg-group ul li a.delete{display:inline-block;line-height:20px;height:20px;width:20px;border-radius:10px;text-align:center;overflow:hidden;position:absolute;right:5px;top:5px;text-decoration:none;background: rgba(0,0,0,0.5);font-size:16px;color:#fff; cursor: pointer;}
</style>

@php
    $input_value = isset($input_value)?$input_value:[];
    $ids = [];
    foreach($input_value as $val){
        $ids[] = $val['md5'];
    }
    $ids = implode(',',$ids);
@endphp

<div id="upload-{{$input_name}}">
    <label id="{{$input_name}}_btn" class="u-dmuploader">
        <span role="button" class="btn btn-sm btn-primary"><span class="fa fa-upload"></span> 多图上传</span>
        <input type="file" name="files[]" class="hide">
    </label>

    <input type="hidden" data-o="{{$ids}}" name="{{$input_name}}" value="{{$ids}}">
    <input type="hidden" name="pic_not_use_id[]" value="">
    <input type="hidden" name="pic_use_id[]" value="">


    <div class="m-uploadimg-group">
        <ul>
            @foreach($input_value as $vo)
                @if($vo)
                    <li data-md5="{{$vo['md5'] or ''}}">
                        <img src="{{isset($vo['md5'])?'/image/'.$vo['md5']:''}}"/>
                        <span class="size">{{ $vo['width'] or '' }}×{{ $vo['height'] or '' }}</span>
                        <a class="prev">‹</a>
                        <a class="next">›</a>
                        <a class="delete">×</a>
                    </li>
                @endif
            @endforeach
        </ul>
        <div class="clear"></div>
    </div>

    <!--克隆代码段-->
    <li class="fun-li-clone hide">
        <img src=""/>
        <span class="size"></span>
        <a class="prev" href="#">‹</a>
        <a class="next" href="#">›</a>
        <a class="delete" href="#">×</a>
    </li>


</div>

<script>

    //插件地址：https://github.com/danielm/uploader
    $('#{{$input_name}}_btn').dmUploader({
        url: '/admin/upload/ajax_upload_img',
        dataType: 'json',
        maxFileSize : {{isset($size)?$size:(int)ini_get('upload_max_filesize')*1024}}*1024,  //允许上传的大小，单位KB
        allowedTypes: 'image/*',
        multiple:true,
        extraData:{
        _token: $('meta[name="csrf-token"]').attr('content'),
            max_width : {{$max_width or 0}},
        max_height : {{$max_height or 0}},
    },
    onComplete: function(){
        //$.danidemo.addLog('#demo-debug', 'default', 'All pending tranfers completed');
        console.log('All pending tranfers completed');
    },
    onUploadProgress: function(id, percent){
        //var percentStr = percent + '%';
        //$.danidemo.updateFileProgress(id, percentStr);
    },
    onUploadSuccess: function(id, res){
        //如果上传失败
        if(res.status == 0){
            $boot.warn({text:res.msg});
        }else {
            //添加到ul后面
            add_group{{ $input_name }}(res.data.md5,res.data.width,res.data.height);

            //重新计算input值、no_use_id、use_id
            reset_group{{ $input_name }}();
            $boot.success({text:'上传成功'});
        }
    },
    onUploadError: function(id, message){
        console.log(message);
        console.log('Failed to Upload file #' + id + ': ' + message);
    },
    onFileTypeError: function(file){
        console.log('File \'' + file.name + '\' cannot be added: must be an image');
        //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' cannot be added: must be an image');
    },
    onFileSizeError: function(file){
        console.log('File \'' + file.name + '\' cannot be added: size excess limit');
        //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' cannot be added: size excess limit');
    },
    onFileExtError: function(file){
        console.log('File \'' + file.name + '\' has a Not Allowed Extension');
        //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' has a Not Allowed Extension');
    },
    onFallbackMode: function(message){
        console.log('Browser not supported(do something else here!): ' + message);
        //$.danidemo.addLog('#demo-debug', 'info', 'Browser not supported(do something else here!): ' + message);
    }
    });


    //插入新图片
    function add_group{{ $input_name}}($md5,$width,$height){
        var $a = $("#upload-{{$input_name}} .fun-li-clone").clone(true).removeClass().attr('data-md5',$md5); //移除所有class
        $a.find('.size').text($width+"×"+$height);
        $a.find('img').attr('src','/image/'+$md5);
        $a.appendTo('#upload-{{$input_name}} ul');
    };

    //重新计算
    function reset_group{{ $input_name}}(){
        //原md5值赋给not_use
        var $old_md5 = $("input[name='{{$input_name}}']").attr('data-o');
        $("#upload-{{$input_name}}").find("input[name='pic_not_use_id[]']").val($old_md5);
        //取新的md5值
        var $new_mds = [];
        $("#upload-{{$input_name}} ul li").each(function(){
            $new_mds.push($(this).attr('data-md5'));
        });
        $new_mds = $new_mds.join(',');
        //新md5值赋给use
        $("#upload-{{$input_name}}").find("input[name='pic_use_id[]']").val($new_mds);
        //新md5值赋给input
        $("input[name='{{$input_name}}']").val($new_mds);
    }

    //只重新计算位置更换（调整顺序情况下）
    function reset_group_sort{{ $input_name}}(){
        //取新的md5值
        var $new_mds = [];
        $("#upload-{{$input_name}} ul li").each(function(){
            $new_mds.push($(this).attr('data-md5'));
        });
        $new_mds = $new_mds.join(',');
        //新md5值赋给input
        $("input[name='{{$input_name}}']").val($new_mds);
    }

    //删除当前选中缩略图
    $("#upload-{{ $input_name }} a.delete").click(function(){
        $(this).parent().remove();
        reset_group{{ $input_name}}();
        return false;
    });
    //图片前移
    $("#upload-{{ $input_name }} a.prev").click(function(){
        var $index = $(this).parent().prevAll().length;
        if($index != 0){
            var $target = $(this).parent().prev();
            $(this).parent().insertBefore($target);
        }
        reset_group_sort{{ $input_name}}();
        return false;
    });
    //图片后移
    $("#upload-{{ $input_name }} a.next").click(function(){
        var $index = $(this).parent().nextAll().length;
        if($index != 0){
            var $target = $(this).parent().next();
            $(this).parent().insertAfter($target);
        }
        reset_group_sort{{ $input_name}}();
        return false;
    });

</script>