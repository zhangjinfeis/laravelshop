{{--
编辑器参数：
input_name:编辑器
input_value:编辑器内容
height:高度(可选)
width:宽度(可选)
custom:编辑器类型,对应ckeditor/custom下的js名称
--}}
<textarea name="{{$input_name}}" class="ckeditor">{{$input_value or ""}}</textarea>
<script>
    var editor_{{$input_name}} = CKEDITOR.replace( '{{$input_name}}', {
        language: 'zh-cn'
        ,filebrowserImageUploadUrl:'{{url('admin/upload/ajax_ckeditor_img')}}?_token={{csrf_token()}}'
        @if(isset($height))
        ,height:'{{$height}}px'
        @endif
        @if(isset($width))
        ,width:'{{$width}}px'
        @endif
        @if(isset($custom))
        ,customConfig : 'custom/{{$custom}}.js'
        @endif
    });
</script>
