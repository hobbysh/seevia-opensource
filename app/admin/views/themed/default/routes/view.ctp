<style>
	.am-radio, .am-checkbox{display:inline;}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
	label{font-weight:normal;}
	.am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
	.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('Route',array('action'=>'view/'.(isset($route["Route"]["id"])?$route["Route"]["id"]:''),'name'=>'userformedit','onsubmit'=>'return check_submit();'));?>
<input type="hidden" name="data[Route][id]" value="<?php echo isset($route['Route']['id'])?$route['Route']['id']:'';?>">
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion" >
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['custom_path']?></th>
                        <td><input type="text" value ="/" readonly=readonly style="width:25px; margin-right: 0; border-right: 0 solid #FFFFFF;float:left;" /><input style="width:200px;float:left;margin-left: 0; border-left: 0 solid #FFFFFF;" type="text" id="Route_url"  name="data[Route][url]" value="<?php echo isset($route['Route']['url'])?$route['Route']['url']:'';?>" onchange="checkrouteurl()"/><input type="hidden" id="route_url_h" value="0"><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['quick_set_up']?></th>
                        <td>
                            <?php echo $this->element('select_homepage');?>
                        </td>
                    </tr>
                    <tr><th  style="padding-top:15px" rowspan="5" ><?php echo $ld['url']?></th></tr>
                    <tr>
                        <td>
                            <span style="float:left;display: inline-block;text-align: left;width: 60px;padding-left:5px;padding-top:10px"><?php echo $ld['controller']?></span>
                            <input style="width:200px;float:left;" type="text" id="controller" name="data[Route][controller]" value="<?php echo isset($route['Route']['controller'])?$route['Route']['controller']:'';?>"/><em>*</em>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="float:left;display: inline-block;text-align: left;width: 60px;padding-left:5px;padding-top:10px"><?php echo $ld['method']?></span>
                            <input style="width:200px;float:left;" type="text" id="action" name="data[Route][action]" value="<?php echo isset($route['Route']['action'])?$route['Route']['action']:'';?>"/><em>*</em>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="float:left;display: inline-block;text-align: left;width: 60px;padding-left:5px;padding-top:10px"><?php echo $ld['modelID']?></span>
                            <input style="width:200px;float:left;" type="text" id="modelID" name="data[Route][model_id]" value="<?php echo isset($route['Route']['model_id'])?$route['Route']['model_id']:'';?>"/><em>*</em>
                        </td>
                    </tr>
                    <tr>
                        <td><span style="float:left;display: inline-block;text-align: left;width: 60px;padding-left:5px;padding-top:10px"><?php echo $ld['option']?></span><input style="width:200px;" type="text" name="data[Route][options]" value="<?php echo isset($route['Route']['options'])?$route['Route']['options']:'';?>"/> <span style="padding-left:85px;"><?php echo $ld['keywords_separated_by_commas'];?></span></td>
                    </tr>
                    <tr> 
                        <th style="padding-top:15px"><?php echo $ld['status']?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[Route][status]" value="1" data-am-ucheck <?php echo !isset($route['Route']['status'])||(isset($route['Route']['status'])&&$route['Route']['status']==1)?"checked":"";?>><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Route][status]" value="0" data-am-ucheck <?php echo !isset($route['Route']['status'])||(isset($route['Route']['status'])&&$route['Route']['status']==0)?"checked":"";?>><?php echo $ld['no']?><em style="top:0px">*</em></label></td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['d_submit']?>" id="submit" />  <input class="am-btn am-btn-success am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function check_submit(){
        var route_urls = document.getElementById("route_url_h").value;
        if(route_urls==1){
            alert(url_already_exist_route);
            return false;
        }else{
            return true;
        }
    }

    function checkrouteurl(){
        var route_url = document.getElementById("Route_url").value;
        if(route_url!=""){
        	var rUrl = "/admin/routes/select_route_url/";//访问的URL地址
        	$.ajax({
	            type: "POST",
	            url: rUrl,
	            dataType: 'json',
	            data: {route_url:route_url},
	            success: function (result) {
	                if(result.type==1){
                        document.getElementById("route_url_h").value=1;
                    }else{
                        document.getElementById("route_url_h").value=0;
                    }
	            }
	        });
        }
    }
</script>