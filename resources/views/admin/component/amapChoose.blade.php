{{--
百度地图设置经纬度插件
param参数:
width:地图宽度，默认100%，,选填
height:地图高度，默认450px,选填
input_lng:经度input名称，必填
input_lat:纬度input名称，必填
input_zoom:缩放级别input名称,选填
lng:经度默认值,选填
lat:纬度默认值,选填
zoom:缩放级别默认值,选填
--}}
<div id="{{$input_lng}}map" style="width:{{$width or '100%'}};height:{{$height or '400px'}};overflow: hidden;margin:0;font-family:'微软雅黑';"></div>
<div style="margin:5px 0 0 0;">
    经度：<span class="{{$input_lng}}fun-lng">{{$lng or '0'}}</span>&nbsp;&nbsp;&nbsp;&nbsp;纬度：<span class="{{$input_lng}}fun-lat">{{$lat or '0'}}</span>
    <span class="c999">&nbsp;&nbsp;&nbsp;&nbsp;提示：拖拽地图选择位置</span>
</div>

<input type="hidden" name="{{$input_lng}}" value="{{$lng or ''}}">
<input type="hidden" name="{{$input_lat}}" value="{{$lat or ''}}">
@if(isset($input_zoom))
    <input type="hidden" name="{{$input_zoom}}" value="{{$zoom or '15'}}">
@endif


<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.4&key=947dd69d7bf6cf026f8a4814d6d12cbd"></script>
<!-- UI组件库 1.0 -->
<script src="https://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
<script type="text/javascript">
    (function(){


            var mp = new AMap.Map("{{$input_lng}}map",{
                zoom:15,
                scrollWheel: false
            });

            @if(isset($lng) && $lng && isset($lat) && $lat)
            mp.setCenter([{{$lng}}, {{$lat}}]);
            @else
            mp.setCity('武汉市');
            @endif

            //更新缩放级别
            @if(isset($input_zoom))
            AMap.event.addListener(mp,'zoomend',function(){
                $('input[name={{$input_zoom}}]').val(mp.getZoom());
            });
            @endif

            AMapUI.loadUI(['misc/PositionPicker'], function(PositionPicker) {
                var positionPicker = new PositionPicker({
                    mode: 'dragMap',
                    map: mp,
                    iconStyle:{//自定义外观
                        url:'/resources/admin/images/position-picker.png',//图片地址
                        size:[36,36],  //要显示的点大小，将缩放图片
                        ancher:[18,36],//锚点的位置，即被size缩放之后，图片的什么位置作为选中的位置
                    }
                });

                positionPicker.on('success', function(positionResult) {
                    var p = positionResult.position.toString();
                    p = p.split(',');
                    $('input[name={{$input_lng}}]').val(p[0]);
                    $('input[name={{$input_lat}}]').val(p[1]);
                    $('.{{$input_lng}}fun-lng').text(p[0]);
                    $('.{{$input_lng}}fun-lat').text(p[1]);
                });
                positionPicker.on('fail', function(positionResult) {
                    $('input[name={{$input_lng}}]').val('');
                    $('input[name={{$input_lat}}]').val('');
                    $('.{{$input_lng}}fun-lng').text('');
                    $('.{{$input_lng}}fun-lat').text('');
                });
                positionPicker.start();
            });


            AMapUI.loadUI(['control/BasicControl'], function(BasicControl) {
                mp.addControl(new BasicControl.Zoom({
                    showZoomNum: false
                }))

            })




    })();


</script>


