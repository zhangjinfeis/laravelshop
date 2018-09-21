
@foreach($power as $vo)
    <div>
        <div>{{$vo['group']}}</div>
        <div>
            @foreach($vo['child'] as $voo)
                <div class="custom-control custom-control-inline custom-checkbox">
                    <input disabled="true" name="power[]" type="checkbox" class="custom-control-input" id="chcek{{$voo['id']}}" value="{{$voo['id']}}" {{$voo['checked']}} />
                    <label class="custom-control-label" for="chcek{{$voo['id']}}">{{$voo['description']}}</label>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

