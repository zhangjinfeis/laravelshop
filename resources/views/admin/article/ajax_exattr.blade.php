@foreach($exattr as $vo)
    <div class="form-group">
        <label>{{$vo['name']}}</label>
        @switch($vo['type'])
            @case(1)
                <input type="text" class="form-control w400" name="exattr[{{$vo['key']}}]" placeholder="{{$vo['name']}}" value="{{$vo['value'] or ''}}" />
            @break
            @case(2)
                <textarea class="form-control w600" rows="3" name="exattr[{{$vo['key']}}]" placeholder="{{$vo['name']}}">{{$vo['value'] or ''}}</textarea>
            @break

            @case(3)
                @component('admin.component.upload_img',array("input_id"=>md5($vo['key']),"input_name"=>"exattr[".$vo['key']."]",'width'=>$vo['width'],'height'=>$vo['height'],'input_value'=>$vo['value']??'','size'=>$vo['size']??''))@endcomponent
            @break

            @case(4)
            @component('admin.component.upload_imgs',array("input_id"=>md5($vo['key']),"input_name"=>"exattr[".$vo['key']."]",'width'=>$vo['width'],'height'=>$vo['height'],'input_value'=>$vo['value']??'','size'=>$vo['size']??''))@endcomponent
            @break

            @case(5)
            @component('admin.component.ckeditor',array("input_id"=>md5($vo['key']),"input_name"=>"exattr[".$vo['key']."]",'width'=>$vo['width'],'height'=>$vo['height'],'input_value'=>$vo['value']??'','custom'=>$vo['custom']??''))@endcomponent
            @break

        @endswitch
        <small class="form-text text-muted">
            @if($vo['tips'])
                {{$vo['tips']}}&nbsp;&nbsp;&nbsp;
            @endif
        </small>
    </div>
@endforeach