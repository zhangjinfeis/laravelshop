{{--
编辑器参数：
必选：input_id,input_name
可选：width,height,custom
值：input_value

**custom:编辑器类型,对应ckeditor/custom下的js名称
--}}
<textarea id="{{$input_id}}" name="{{$input_name}}" class="ckeditor">{{$input_value or ""}}</textarea>
<script type="text/javascript" src="/resources/ckeditor/ckeditor.js"></script>
<script>
    var editor_{{$input_id}} = CKEDITOR.replace( '{{$input_id}}', {
        language: 'zh-cn'
        ,filebrowserImageUploadUrl:'{{url('admin/upload/ajax_ckeditor_img')}}?_token={{csrf_token()}}'
        @if(isset($height)&&$height)
        ,height:'{{$height}}px'
        @else
        ,height:'400px'
        @endif
        @if(isset($width)&&$width)
        ,width:'{{$width}}px'
        @else
        ,width:'100%'
        @endif
        @if(isset($custom))
        ,customConfig : 'custom/ckeditor_{{$custom}}.js'
        @endif
    });
    editor_{{$input_id}}.on('change',function(){
        var bd = editor_{{$input_id}}.getData();
        $("#{{$input_id}}").val(bd);
    });
</script>
