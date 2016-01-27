<?php
/*****************************************************************************
 * SV-Cart  编辑角色
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>

<style>
 .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;} 
 .am-form-horizontal .am-checkbox{padding-top:0;}
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
</style>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 1000; width: 15%;max-width:200px;">
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#select_all"><?php echo $ld['select_all']?></a></li>
			<?php if(isset($operatoraction) && sizeof($operatoraction)>0){foreach($operatoraction as $k=>$v){?>
				<li><a href="#<?php echo $v['OperatorAction']['name']?>"><?php echo $v['OperatorAction']['name']?></a></li>
			<?php }}?>
		</ul>
	</div>
	<?php echo $form->create('Role',array('class'=>'am-form am-form-horizontal','action'=>'add/'));?>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<div id="basic_information" class="am-panel am-panel-default">
		    <div class="am-panel-hd">
			    <h4 class="am-panel-title">
					<label><?php echo $ld['basic_information'] ?></label>
					<input type="hidden" name="data[OperatorRole][id]" value="<?php echo $operatorrole['OperatorRole']['id']?>" />			
				</h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
			    <div class="am-panel-bd ">
			    	<div class="am-form-group">
					    <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
					    	<?php echo $ld['role_role_name']?>
					    </label>
					   	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="margin-bottom:10px;">
								<input type="text" id="name<?php echo $v['Language']['locale']?>" name="data[OperatorRoleI18n][<?php echo $k;?>][name]" value="<?php if(!empty($operatorrole['OperatorRoleI18n'][$v['Language']['locale']]['name'])){echo $operatorrole['OperatorRoleI18n'][$v['Language']['locale']]['name'];}?>" />
							</div>
								<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:21px;">
									<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
								</label>
								<?php }?>
						<?php }} ?>
						</div>
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
							<input name="data[OperatorRoleI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo  $v['Language']['locale'];?>">
							<input id="OperatorRoleI18n<?php echo $k;?>Id" name="data[OperatorRoleI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo @$this->data['OperatorRoleI18n'][$v['Language']['locale']]['id'];?>">
							<input id="OperatorRoleI18n<?php echo $k;?>OperatorRoleId" name="data[OperatorRoleI18n][<?php echo $k;?>][operator_role_id]" type="hidden" value="<?php echo  $this->data['OperatorRole']['id'];?>">
							</div>
						<?php }}?>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;"><?php echo $ld['number']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php echo $operatorrole['OperatorRole']['id']?><input type="hidden" class="text_inputs"  name="data[OperatorRole][id]" value="<?php echo $operatorrole['OperatorRole']['id']?>" />
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['sort']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<input class="am-form-field am-radius" name="data[OperatorRole][orderby]" value="<?php echo $operatorrole['OperatorRole']['orderby']?>"/>
							</div><div style="margin-top:5px;">
								<?php echo $ld['role_sort_default_num']?></div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;"><?php echo $ld['valid']?></label>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
							<label class="am-radio am-success"><input type="radio" name="data[OperatorRole][status]" value="1" data-am-ucheck checked style="margin-left:0px;" />&nbsp;<?php echo $ld['yes']?>&nbsp;&nbsp;</label>
							<label class="am-radio am-success"><input type="radio" name="data[OperatorRole][status]" value="0" data-am-ucheck style="margin-left:0px;" />&nbsp;<?php echo $ld['no']?></label>
						</div>
					</div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="" ><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>				
				</div>
			</div>
		</div>
		<div id="select_all" class="am-panel am-panel-default">
		    <div class="am-panel-hd">
			    <h4 class="am-panel-title">
			    	<label class="am-checkbox am-success" style="font-weight:bold;">
			    		<input type="checkbox" name="checkbox" value="checkbox" data-am-ucheck onclick="checkAll(this.form, this);" >
			    	    &nbsp;<?php echo $ld['select_all']?>
			    	</label>
			    </h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
			    <div class="am-panel-bd ">
			    	<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="" ><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>
			    </div>
			</div>	
		</div>
		<?php if(isset($operatoraction) && sizeof($operatoraction)>0){foreach($operatoraction as $k=>$v){?>
		<div id="<?php echo $v['OperatorAction']['name']?>" class="am-panel am-panel-default">
		    <div class="am-panel-hd">
			    <h4 class="am-panel-title">
				    <label class="am-checkbox am-success" style="font-weight:bold;">
				    	<input type="checkbox" data-am-ucheck  onclick='checkall(this)'/>
				    	<?php echo $v['OperatorAction']['name']?>
				   	</label>
			    </h4>
			</div>
			<div class="OperatorAction_list">
				<?php if(isset($v['SubAction']) && sizeof($v['SubAction'])>0)foreach($v['SubAction'] as $vv){?>
				<div class="am-form-group" style="margin-left:9%">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:bold;cursor: pointer;margin-left:12px;"> 
						<input type="checkbox" name='<?php echo "ops_".$v["OperatorAction"]["id"];?>' data-am-ucheck value="<?php echo $vv['OperatorAction']['id']?>" onclick="checktr(this)" />
						&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $vv['OperatorAction']['name']?>
					</label>
					<div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
					<?php if(isset($vv['SubAction']) && sizeof($vv['SubAction'])>0){?>
                        <ul class="am-avg-lg-5">
						<?php foreach($vv['SubAction'] as $vvv){?>
                            <li>
							<label class="am-checkbox am-success">
								<input type="checkbox" name="competence[]" data-am-ucheck value="<?php echo $vvv['OperatorAction']['id']?>" <?php if(in_array($vvv['OperatorAction']['id'],$actions_arr)) echo 'checked';?> />
								<?php echo $vvv['OperatorActionI18n']['name']?>
							</label>
						<?php }?>&nbsp;
                            </li>
                        </ul>
					<?php }?>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		<?php }}?>
    	<div class="btnouter" style="margin-top:20px; ">
			<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="" ><?php echo $ld['d_submit'];?></button>
			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
		</div>
	</div>
<?php echo $form->end();?>
</div>
<script language="javascript">
	function show_intro(pre,pree, n, select_n,css) {
		for (var i = 1; i <= n; i++) {
			var intro = document.getElementById(pre + i);
			var cha = document.getElementById(pree + i);
			intro.style.display = "none";
			cha.className=css + "_off";
			if (i == select_n) {
				intro.style.display = "block";
				cha.className=css + "_on";
			}
		}
	}
//操作员复选框全部选取
function checkAll(frm, checkbox){
	for(var i = 0; i < frm.elements.length; i++){
		if( frm.elements[i].type == "checkbox" ){
			frm.elements[i].checked = checkbox.checked;
		}
	}
}


function checktr(obj){
	var checkTr =  $(obj).parent().parent();
	var checkbox = checkTr.find("div input[type=checkbox]");
	var checkStatus = obj.checked;
	for(var i=0;i<checkbox.length;i++){
		checkbox[i].checked = checkStatus;
	}
}
function checkall(obj){
	var checkTable = $(obj).parent().parent().parent().parent();
	var checkbox = checkTable.find(".OperatorAction_list input[type=checkbox]");
	var checkStatus = obj.checked;
	for(var i=0;i<checkbox.length;i++){
		checkbox[i].checked = checkStatus;
	}
}

function checkall2(obj){
	var checkboxs = document.getElementsByName(obj);
	for(var i=checkboxs.length;i--;){
		checkboxs[i].click();
	}
}

</script>
