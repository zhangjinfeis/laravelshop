<style>
    body { margin: 0; font: 13px/1.5 "Microsoft YaHei", "Helvetica Neue", "Sans-Serif"; min-height: 960px; min-width: 600px; }
    .my-map { margin: 0 auto; width: 100%; height: 300px; }
    .amap-container{height: 100%;}
</style>

<div id="wrap" class="my-map">
    <div id="mapContainer"></div>
</div>
<script src="//webapi.amap.com/maps?v=1.3&key=8325164e247e15eea68b59e89200988b"></script>
<script>
    !function(){
        var marker, map = new AMap.Map("mapContainer", {
            resizeEnable: true,
            center: [114.206089,30.551582],
            zoom: 15
        });

        function addMarker() {
            marker = new AMap.Marker({
                icon: "http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
                position: [114.206089,30.551582]
            });
            marker.setMap(map);
        }
        addMarker();
    }();
</script>