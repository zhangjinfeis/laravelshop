<!--
百度地图设置经纬度插件
param必填参数:
id:唯一性标识
width:地图宽度
height:地图高度
lng:经度默认值
lat:纬度默认值
zoom:缩放级别默认值
title:标题
address:标题
-->
<div id="{{$id}}map_show" style="width:{{$width}}px;height:{{$height}}px;overflow: hidden;margin:0;font-family:'微软雅黑';"></div>
<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.4&key=947dd69d7bf6cf026f8a4814d6d12cbd"></script>
<!-- UI组件库 1.0 -->
<script src="https://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
<script type="text/javascript">
    (function(){
        var map = new AMap.Map('{{$id}}map_show', {
            resizeEnable: true,
            zoom:{{$zoom}},
            center: [{{$lng}}, {{$lat}}]
        });

        // 实例化点标记
        function addMarker() {
            marker = new AMap.Marker({
                icon: "https://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
                position: [{{$lng}}, {{$lat}}]
            });
            marker.setMap(map);
            //鼠标点击marker弹出自定义的信息窗体
            AMap.event.addListener(marker, 'click', function() {
                openInfo();
            });
        }
        addMarker();

        //在指定位置打开信息窗体
        function openInfo() {
            //构建信息窗体中显示的内容
            var info = [];
            info.push("<div style=\"padding:0px 0px 0px 4px;\"><b>{{$title}}</b>");
            info.push("{{$address}}</div>");
            infoWindow = new AMap.InfoWindow({
                content: info.join("<br/>"),  //使用默认信息窗体框样式，显示信息内容
                offset: new AMap.Pixel(0, -20)
            });
            infoWindow.open(map, [{{$lng}}, {{$lat}}]);
        }



    })();
</script>


