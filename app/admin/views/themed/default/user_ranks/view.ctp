<?php 
/*****************************************************************************
 * SV-Cart  添加会员等级
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
*****************************************************************************/
?>
<style type="text/css">
	label{font-weight:normal;}
	.am-form-label{font-weight:bold;}
	.am-radio input[type="radio"]{margin-left:0px;}
	.btnouter{margin:50px;}
    .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#member_level"><?php echo $ld['member_level']?></a></li>
		</ul>
	</div>	
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo $form->create('UserRank',array('action'=>'/view/'.$id,'name'=>"SeearchForm",'id'=>"SearchForm","type"=>"post",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
			<?php if(isset($backend_locales) && sizeof($backend_locales) > 0){foreach($backend_locales as $k => $v){?>
				<input id="UserRankI18n<?php echo $k;?>Locale" name="data[UserRankI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo @$v['Language']['locale'];?>">
				<?php if(isset($this->data['UserRankI18n'][$v['Language']['locale']])){?>
				<input id="UserRankI18n<?php echo $k;?>Id" name="data[UserRankI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo @$this->data['UserRankI18n'][$v['Language']['locale']]['id'];?>"> 
				<input id="UserRankI18n<?php echo $k;?>UserRankId" name="data[UserRankI18n][<?php echo $k;?>][user_rank_id]" type="hidden" value="<?php echo @$this->data['UserRank']['id'];?>">
			<?php }}}?>
			<input type="hidden" name="data[UserRank][id]" id="UserRankId" value="<?php echo $id; ?>" />
			<div id="member_level" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
                    <div class="am-panel-bd am-form-detail">
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['member_level'].$ld['code'];?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    		    					<lable>
    		    						<input type="text" name="data[UserRank][code]" value="<?php echo isset($userrank['UserRank']['code'])?$userrank['UserRank']['code']:'0'; ?>" id="UserRank_code" style="ime-mode:disabled;" onblur="check_code(this,true);" />
    		    					</lable>
    		    				</div>
    		    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:20px;"><em style="color:red;">*</em></label>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['name']?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
    		    					<input type="text" id="user_rank_name_<?php echo $v['Language']['locale']?>" name="data[UserRankI18n][<?php echo $k;?>][name]" value="<?php echo isset($userrank['UserRankI18n'][$v['Language']['locale']]['name'])?$userrank['UserRankI18n'][$v['Language']['locale']]['name']:'';?>" />
    		    				</div>
    	    					<?php if(sizeof($backend_locales)>1){?>
    	    						<label class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="padding-top:17px;"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
    	    					<?php }?>
    		    				<?php }} ?>		
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
    		    				<?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['level'].' '.$ld['amount']:$ld['level'].$ld['amount'];?>:
    		    			</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    								<lable><input type="text" name="data[UserRank][balance]" value="<?php echo isset($userrank['UserRank']['balance'])?$userrank['UserRank']['balance']:'0'; ?>" style="ime-mode:disabled;" onkeydown="return check_balance(event)" /></lable>
    		    				</div>
    		    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:18px;"><em style="color:red;">*</em></label>		
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['point_toplimit']?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    								<lable>
    									<input type="text" name="data[UserRank][max_points]" style="ime-mode:disabled;" value="<?php echo isset($userrank['UserRank']['max_points'])?$userrank['UserRank']['max_points']:'0'; ?>" maxlength="20" onkeydown="return check_number(event)" />
    								</lable>
    		    				</div>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:22px;"><?php echo $ld['discount_rate']?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    								<lable><input type="text" name="data[UserRank][discount]" style="ime-mode:disabled;" value="<?php echo isset($userrank['UserRank']['discount'])?$userrank['UserRank']['discount']:'0'; ?>" maxlength="3" onkeydown="return check_number(event)" /></lable>
    		    				</div>	
    		    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:20px;"><em style="color:red;">*</em></label>
    								<label style="font-size:12px;padding-left:20px;"><?php echo $ld['initial_discount_info']?></label>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['point_lowerlimit']?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    								<lable>
    									<input type="text" name="data[UserRank][min_points]" style="ime-mode:disabled;" value="<?php echo isset($userrank['UserRank']['min_points'])?$userrank['UserRank']['min_points']:'0'; ?>" maxlength="20" onkeydown="return check_number(event)" />
    								</lable>
    		    				</div>		
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['salable']?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserRank][allow_buy]" data-am-ucheck  value="1" <?php echo (isset($userrank['UserRank']['allow_buy'])&&$userrank['UserRank']['allow_buy']=='1')||(!isset($userrank['UserRank']['allow_buy']))?'checked':''; ?>/><?php echo $ld['yes']?>
    								</label>&nbsp;&nbsp;
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserRank][allow_buy]" data-am-ucheck  value="0" <?php echo (isset($userrank['UserRank']['allow_buy'])&&$userrank['UserRank']['allow_buy']=='0')?'checked':''; ?> /><?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['special_members']?>: </label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    								<label class="am-radio am-success" style="padding-top:2px;"> 
    									<input type="radio" name="data[UserRank][special_rank]" data-am-ucheck  value="1" <?php echo (isset($userrank['UserRank']['special_rank'])&&$userrank['UserRank']['special_rank']=='1')||(!isset($userrank['UserRank']['special_rank']))?'checked':''; ?>/><?php echo $ld['yes']?>
    								</label>&nbsp;&nbsp;
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserRank][special_rank]" data-am-ucheck  value="0" <?php echo (isset($userrank['UserRank']['special_rank'])&&$userrank['UserRank']['special_rank']=='0')?'checked':''; ?>/><?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['show_price']?>:</label>
    		    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    			    				<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserRank][show_price]" data-am-ucheck  value="1" <?php echo (isset($userrank['UserRank']['show_price'])&&$userrank['UserRank']['show_price']=='1')||(!isset($userrank['UserRank']['show_price']))?'checked':''; ?>/><?php echo $ld['yes']?>
    								</label>&nbsp;&nbsp;
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserRank][show_price]" data-am-ucheck  value="0" <?php echo (isset($userrank['UserRank']['show_price'])&&$userrank['UserRank']['show_price']=='0')?'checked':''; ?>/><?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>
                    </div>
				</div>
				<div class="btnouter">
					<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
					<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				</div>	
			</div>
		<?php echo $form->end();?>
	</div>
</div>

<script type="text/javascript">
var UserRank_code_falg=false;
check_code(document.getElementById("UserRank_code"),false);

function formsubmit(){
	var name=document.getElementById("user_rank_name_chi");
    var UserRank_code=document.getElementById("UserRank_code").value;
	var balance=document.getElementsByName("data[UserRank][balance]")[0];
	var discount=document.getElementsByName("data[UserRank][discount]")[0];
	var max_points=document.getElementsByName("data[UserRank][max_points]")[0];
	var min_points=document.getElementsByName("data[UserRank][min_points]")[0];
    
    if(!UserRank_code.length>0){
        alert('请输入会员等级编号！');
		return false;
    }else if(!UserRank_code_falg){
        alert('会员等级编号已被使用!');
        return false;
    }else if(!name.value.length>0){
		alert('请输入会员等级名称！');
		return false;
	}else if(document.getElementById("user_rank_name_eng")&&document.getElementById("user_rank_name_eng").value.length<=0){
		alert('请输入会员等级名称！');
		return false;
	}else if(balance.value==''){
		alert('请输入会员等级金额!');
		return false;
	}else if(!/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/.test(balance.value)){
		alert('金额格式错误!');
		return false;
	}else if(!discount.value.length>0){
		alert('请确定初始折扣率!');
		return false;
	}else if(discount.value>100)
	{
		alert('初始折扣率超出范围!');
		return false;
	}else if(min_points.value>max_points.value){
		alert('积分下限不能大于积分上限!');
		return false;
	}
	return true;
}

function check_code(obj,action_flag){
    var UserRank_code=obj.value;
    var UserRank_Id=document.getElementById('UserRankId').value;
    var PostData={"UserRank_code":UserRank_code,"UserRank_Id":UserRank_Id};
    if(UserRank_code==""){UserRank_code_falg=false;return false;}
    $.ajax({
    	url:"/admin/user_ranks/check_code/",
    	type:"POST",
    	data:PostData,
    	dataType:"json",
    	success:function(data){
    		if(data.code=='1'){
                UserRank_code_falg=true;
            }else{
                UserRank_code_falg=false;
                if(action_flag){alert(data.msg);}
            }
    	}
    });
    
    
   /* YUI().use("io",function(Y) {
    		var sUrl = "/admin/user_ranks/check_code/";
    		var cfg = {
    				method: 'POST',
    				data:PostData
    			};
    		var request = Y.io(sUrl, cfg);//开始请求
    		var handleSuccess = function(ioId, o){
    			try{
					eval('var result='+o.responseText);
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}
                if(result.code=='1'){
                    UserRank_code_falg=true;
                }else{
                    UserRank_code_falg=false;
                    if(action_flag){alert(result.msg);}
                }
    		};
    		var handleFailure = function(ioId, o){
    			alert(o.responseText);
    		}
    		Y.on('io:success', handleSuccess);
    		Y.on('io:failure', handleFailure);
    	});*/
}

//只能输入数字
function check_number(e){
	if((e.keyCode>=48&&e.keyCode<=57)||(e.keyCode>=96&&e.keyCode<=105)||e.keyCode==8){
		return true;
	}else{
		return false;
	}
}
//金额输入
function check_balance(e){
	if((e.keyCode>=48&&e.keyCode<=57)||(e.keyCode>=96&&e.keyCode<=105)||e.keyCode==8||e.keyCode==110||e.keyCode==190){
		return true;
	}else{
		return false;
	}
}
</script>