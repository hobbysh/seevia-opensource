<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body, html{width: 100%;height: 100%;overflow: hidden;margin:0;}
#allmap {height: 100%;overflow: hidden;}
dl,dt,dd,ul,li{
    margin:0;
    padding:0;
    list-style:none;
}
dt{
    font-size:14px;
    font-family:"微软雅黑";
    font-weight:bold;
    border-bottom:1px dotted #000;
    padding:5px 0 5px 5px;
    margin:5px 0;
}
dd{
    padding:5px 0 0 5px;
}
li{
    line-height:28px;
}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6ba53aa75bbe88cb4f9f31ddfc03a5ec"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
<title><?php echo $ld['shop_map']; ?></title>
</head>
<body>
<input id="position" type="hidden" value="<?php echo $position;?>"/>
<input id="map_locale" type="hidden" value="<?php echo $map_locale;?>">

<div id="allmap" style="overflow:hidden;zoom:1;position:relative;height:95%;">	
    <div id="map" style="height:100%;-webkit-transition: all 0.5s ease-in-out;transition: all 0.5s ease-in-out;"></div>
</div>
<input id="submit_position" onclick="save_map()" type="button" value="<?php echo $ld['save']?>"/>
<script type="text/javascript">
/*取URL参数*/
function getUrlParam(name){
	url = location.href;
	index1 = url.indexOf(name + "=");
	index2 = -1;
	if(index1 != -1){
	index2 = url.indexOf("&",index1+1);
	if(index2 == -1)
	index2 = url.indexOf("#",index1+1);
	}else{
	return "";
	}
	index1 = index1 + name.length + 1;
	if(index2 == -1)
	return url.substr(index1);
	else
	return url.substr(index1,index2-index1);
}
// 百度地图API功能
    var map = new BMap.Map('map');
    //var position=encodeURI(getUrlParam("position"));
    var position="<?php echo isset($position)?$position:'0'; ?>";
    if(position!="0"){
    	point=position.split(",");
    	var poi = new BMap.Point(point[0],point[1]);
    }else{
    	var poi=new BMap.Point(121.407986,31.218038);
    }
    map.centerAndZoom(poi, 16);
    map.enableScrollWheelZoom();
    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮

    var content = '<div style="margin:0;line-height:20px;padding:2px;"></div>';

    //创建检索信息窗口对象
    var searchInfoWindow = null;
	searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
			title  : '',      //标题
			width  : 290,             //宽度
			height : 0,              //高度
			panel  : "panel",         //检索结果面板
			enableAutoPan : true,     //自动平移
			searchTypes   :[
				BMAPLIB_TAB_SEARCH,   //周边检索
				BMAPLIB_TAB_TO_HERE,  //到这里去
				BMAPLIB_TAB_FROM_HERE //从这里出发
			]
		});
    var marker = new BMap.Marker(poi); //创建marker对象
    marker.enableDragging(); //marker可拖拽
    marker.addEventListener("click", function(e){
	    searchInfoWindow.open(marker);
    })
    marker.addEventListener("dragend", function (e) {
    	document.getElementById('position').value=e.point.lng + "," + e.point.lat;
    });
    map.addOverlay(marker); //在地图中添加marker
    searchInfoWindow.open(marker); //在marker上打开检索信息串口

    function $(id){
        return document.getElementById(id);
    }
	function save_map(){
		var shop_map=$("map_locale").value;
		var position=$("position").value;
		window.opener.document.getElementById(shop_map).value=position;
		window.close();
	}
</script>
</body>
</html>