    <link href="/admin/vendors/echarts-2.2.0/doc/asset/css/font-awesome.min.css" rel="stylesheet">
    <link href="/admin/vendors/echarts-2.2.0/doc/asset/css/carousel.css" rel="stylesheet">
    <link href="/admin/vendors/echarts-2.2.0/doc/asset/css/echartsHome.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/admin/vendors/echarts-2.2.0/doc/example/www/js/echarts.js"></script>
    <script src="/admin/vendors/echarts-2.2.0/doc/asset/js/codemirror.js"></script>
    <script src="/admin/vendors/echarts-2.2.0/doc/asset/js/javascript.js"></script>
    <link href="/admin/vendors/echarts-2.2.0/doc/asset/css/monokai.css" rel="stylesheet">
<?php echo $form->create('ContactReport',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div>
	<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		<li>
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">预约时间</label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
				<input id="start_date" type="text" name="start_date" value="<?php echo $start_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
			</div>
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:7px;"><em>-</em></label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
				<input type="text" id="end_date" name="end_date" value="<?php echo $end_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
			</div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				<input type="button" onclick="search_contact_report()"  class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
			</div>
		</li>
	</ul>
</div>
<?php echo $form->end();?>
<script>
function search_contact_report(){
	var start_date=$("#start_date").val();
	var end_date=$("#end_date").val();
	if(start_date==""){
		alert("开始时间不能为空");
		return false;
	}
	if(end_date==""){
		alert("结束时间不能为空");
		return false;
	}
	document.SReportForm.action=admin_webroot+"contact_reports/";
	document.SReportForm.onsubmit= "";
	document.SReportForm.submit();
}
</script>
    <!-- Fixed navbar -->
    <div class="container-fluid">
        <div class="row-fluid example">
            <div id="sidebar-code" class="col-md-4" style="display:none;">
                <div class="well sidebar-nav">
                    <div class="nav-header"><a href="#" onclick="autoResize()" class="glyphicon glyphicon-resize-full" id ="icon-resize" ></a>option</div>
                    <textarea id="code" name="code" >
var color_arr=new Array();//颜色数组
var order_color_arr=new Array();//颜色数组
var date=new Array();//日期
var time_quantum=new Array();//时间段
var time1=new Array();//5个时间段数组
var time2=new Array();//5个时间段数组
var time3=new Array();//5个时间段数组
var time4=new Array();//5个时间段数组
var time5=new Array();//5个时间段数组
var time6=new Array();//5个时间段数组
var time7=new Array();//5个时间段数组
var time8=new Array();//5个时间段数组
var time9=new Array();//5个时间段数组
var time10=new Array();//5个时间段数组
<?php if(isset($date_arr)){foreach($date_arr as $k=>$v){?>
	date[<?php echo $k?>]="<?php echo $v?>";
	color_arr[<?php echo $k?>]='#87cefa';
	order_color_arr[<?php echo $k?>]='#ff0000';
<?php }}?>
<?php if(isset($time_quantum)){foreach($time_quantum as $k=>$v){?>
	time_quantum[<?php echo $k?>]="<?php echo $v?>";
<?php }}?>
<?php if(isset($data_contact_arr)){$i=0;foreach($data_contact_arr as $k=>$v){?>
	<?php if(isset($v["10:30-12:30"])){?>
	//alert("<?php echo $v['10:30-12:30'];?>");
	time1[<?php echo $i;?>]=<?php echo $v["10:30-12:30"];?>;
	<?php }?>
	<?php if(isset($v["12:30-14:30"])){?>
	time2[<?php echo $i;?>]=<?php echo $v["12:30-14:30"];?>;
	<?php }?>
	<?php if(isset($v["14:30-16:30"])){?>
	time3[<?php echo $i;?>]=<?php echo $v["14:30-16:30"];?>;
	<?php }?>
	<?php if(isset($v["16:30-18:30"])){?>
	time4[<?php echo $i;?>]=<?php echo $v["16:30-18:30"];?>;
	<?php }?>
	<?php if(isset($v["18:30-20:30"])){?>
	time5[<?php echo $i;?>]=<?php echo $v["18:30-20:30"];?>;
	<?php }?>
<?php $i++; }}?>
<?php if(isset($data_order_arr)){$j=0;foreach($data_order_arr as $k=>$v){?>
	<?php if(isset($v["10:30-12:30"])){?>
	//alert("<?php echo $v['10:30-12:30'];?>");
	time6[<?php echo $j;?>]=<?php echo $v["10:30-12:30"];?>;
	<?php }?>
	<?php if(isset($v["12:30-14:30"])){?>
	time7[<?php echo $j;?>]=<?php echo $v["12:30-14:30"];?>;
	<?php }?>
	<?php if(isset($v["14:30-16:30"])){?>
	time8[<?php echo $j;?>]=<?php echo $v["14:30-16:30"];?>;
	<?php }?>
	<?php if(isset($v["16:30-18:30"])){?>
	time9[<?php echo $j;?>]=<?php echo $v["16:30-18:30"];?>;
	<?php }?>
	<?php if(isset($v["18:30-20:30"])){?>
	time10[<?php echo $j;?>]=<?php echo $v["18:30-20:30"];?>;
	<?php }?>
<?php $j++; }}?>
var zrColor = require('zrender/tool/color');
var colorList = color_arr;
var itemStyle = {
    normal: {
        color: function(params) {
          if (params.dataIndex < 0) {
            // for legend
            return zrColor.lift(
              colorList[colorList.length - 1], params.seriesIndex * 0.1
            );
          }
          else {
            // for bar
            return zrColor.lift(
              colorList[params.dataIndex], params.seriesIndex * 0.1
            );
          }
        },
        label : {
            show : true,
            textStyle : {
                fontSize : '14',
                fontFamily : '微软雅黑',
                fontWeight : 'bold'
            }
        }
    }
};
var order_colorList = order_color_arr;
var order_itemStyle = {
    normal: {
        color: function(params) {
          if (params.dataIndex < 0) {
            // for legend
            return zrColor.lift(
              order_colorList[order_colorList.length - 1], params.seriesIndex * 0.1
            );
          }
          else {
            // for bar
            return zrColor.lift(
              order_colorList[params.dataIndex], params.seriesIndex * 0.1
            );
          }
        },
        label : {
            show : true,
            textStyle : {
                fontSize : '14',
                fontFamily : '微软雅黑',
                fontWeight : 'bold'
            }
        }
    }
};


option = {
    title: {
        text: '预约时间段的人数统计列表（人）',
        subtext: '',
        sublink: ''
    },
    tooltip: {
        trigger: 'axis',
        backgroundColor: 'rgba(255,255,255,0.9)',
        axisPointer: {
            type: 'shadow'
        },
        formatter: function(params) {
            // for text color
            var color = colorList[params[0].dataIndex];
            var res = '<div style="color:' + color + '">';
            res += '<strong>' + params[0].name + '预约（人）</strong>'
            for (var i = 0, l = params.length; i < l; i++) {
                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value 
            }
            res += '</div>';
            return res;
        }
    },
    legend: {
        x: 'right',
        data:time_quantum
    },
    toolbox: {
        show: true,
        orient: 'vertical',
        y: 'center',
        feature: {
            mark: {show: true},
            dataView: {show: true, readOnly: false},
            restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    calculable: true,
    grid: {
        y: 80,
        y2: 40,
        x2: 40
    },
    xAxis: [
        {
            type: 'category',
            data: date
        }
    ],
    yAxis: [
        {
            type: 'value'
        }
    ],
    series: [
    
        {
            name: time_quantum[0],
            type: 'bar',
            barWidth: 10, 
            itemStyle: itemStyle,
            data:time1
        },
        {
            name: time_quantum[0],
            type: 'bar',
            barWidth: 10, 
            itemStyle: order_itemStyle,
            data:time6
        },
        {
            name: time_quantum[1],
            type: 'bar',
            barWidth: 10, 
            itemStyle: itemStyle,
            data: time2
        },
        {
            name: time_quantum[1],
            type: 'bar',
            barWidth: 10, 
            itemStyle: order_itemStyle,
            data:time7
        },
        {
            name: time_quantum[2],
            type: 'bar',
            barWidth: 10, 
            itemStyle: itemStyle,
            data: time3
        },
        {
            name: time_quantum[2],
            type: 'bar',
            barWidth: 10, 
            itemStyle: order_itemStyle,
            data:time8
        },
        {
            name: time_quantum[3],
            type: 'bar',
            barWidth: 10, 
            itemStyle: itemStyle,
            data: time4
        },
        {
            name: time_quantum[3],
            type: 'bar',
            barWidth: 10, 
            itemStyle: order_itemStyle,
            data:time9
        },
        {
            name:time_quantum[4],
            type:'bar',
            barWidth: 10,                   // 系列级个性化，柱形宽度
            itemStyle: itemStyle,
            data:time5
        },
        {
            name: time_quantum[4],
            type: 'bar',
            barWidth: 10, 
            itemStyle: order_itemStyle,
            data:time10
        }
    ]
};

                    </textarea>
              </div><!--/.well -->
            </div><!--/span-->
            <div id="graphic" class="col-md-12">
                <div id="main" class="main"></div>
                <div style="display:none;">
                    <button type="button" class="btn btn-sm btn-success" onclick="refresh(true)">刷 新</button>
                    <span class="text-primary">切换主题</span>
                    <select id="theme-select"></select>

                    <span id='wrong-message' style="color:red"></span>
                </div>
            </div><!--/span-->
        </div><!--/row-->
        
        </div><!--/.fluid-container-->

    <div id="footer"></div>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/admin/vendors/echarts-2.2.0/doc/asset/js/jquery.min.js"></script>

    <script src="/admin/vendors/echarts-2.2.0/doc/asset/js/echartsExample.js"></script>
    <script>
    	$("#theme-select").change(function(){
    		$("#theme-select").val("default");
    	})
    	
    </script>
<style>
body{padding:0;}
</style>
