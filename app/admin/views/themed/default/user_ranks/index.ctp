
<?php 
/*****************************************************************************
 * SV-Cart  会员等级管理列表
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
<!--Main Start-->

<style>
 .am-checkbox input[type="checkbox"]{margin-left:0;}
 .am-panel-title{font-weight:bold;}
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
</style>
<div>
	<?php if($svshow->operator_privilege("user_ranks_add")){?>	
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;"> 
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/user_ranks/view'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<?php }?>
	<div id="tablelist">
	<?php echo $form->create('user_ranks',array('action'=>'/removeAll','name'=>'UserRankForm','type'=>'post',"onsubmit"=>"return false;"));?>
		<div class="am-panel-group am-panel-tree"  id="accordion">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only ">
							<label class="am-checkbox am-success" style="font-weight:bold;">
							<span class="am-hide-sm-only">	<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span> <?php echo $ld['member_level'].$ld['code'];?>
							</label>
						</div>
		                           <div class="am-u-lg-1 am-u-md-3 am-u-sm-4"><?php echo $ld['member_level'].$ld['name'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['level'].$ld['amount'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['point_lowerlimit'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['point_toplimit'];?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $ld['initial_discount_rate'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['show_price'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['special_members'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['salable'];?></div>
						<div class="am-u-lg-1 am-u-md-4 am-u-sm-5"><?php echo $ld['operate'];?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($userrank_list)&&count($userrank_list)>0){foreach($userrank_list as $k=>$v){?>
			<div>
				<div class=" listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-2  am-u-md-3 am-hide-sm-only">
							<label class="am-checkbox am-success">
								<span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['UserRank']['id']?>" /></span>
								<?php echo $v['UserRank']['code'];?>
							</label>
						</div>
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-4"><?php echo $v['UserRankI18n']['name'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['UserRank']['balance']; ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['UserRank']['min_points']; ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['UserRank']['max_points']; ?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $v['UserRank']['discount']; ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['UserRank']['show_price']=='1'?$ld['yes']:$ld['no']; ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['UserRank']['special_rank']=='1'?$ld['yes']:$ld['no']; ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['UserRank']['allow_buy']=='1'?$ld['yes']:$ld['no']; ?></div>
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-5 am-btn-group-xs am-action">
							<?php if($svshow->operator_privilege("user_ranks_edit")){?>
					 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_ranks/'.$v['UserRank']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
				<?php 	}?>
							<?php if($svshow->operator_privilege("user_ranks_remove")){?>
								<a href="/admin/user_ranks/remove/<?php echo $v['UserRank']['id']; ?>" class="am-btn am-text-danger am-btn-default am-btn-sm am-radius"  onclick="return confirm('<?php echo $ld['confirm_delete'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a>
							<?php }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>
		</div>
	 <?php if(isset($userrank_list)&&count($userrank_list)>0){?>
		<?php if($svshow->operator_privilege("user_ranks_remove")){?>
		<div id="btnouterlist" class="btnouterlist">
            <div class="am-u-lg-3 am-u-md-4 am-u-sm-12  am-hide-sm-down "style='margin-left:7px' >
		        <label class="am-checkbox am-success">
		            <input type="checkbox" data-am-ucheck  onclick="listTable.selectAll(this,'checkboxes[]')"></input>
		            <span><?php echo $ld['select_all'] ?></span>
		        </label>&nbsp;&nbsp;
		    	<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="removeAll()"><?php echo $ld['batch_delete'] ?></button>
		    </div>
		    		
	    <?php }?>
	    	<?php }?>
    	    <div class="am-u-lg-8 am-u-md-7 am-u-sm-12">
    			<?php echo $this->element('pagers');?>
    		</div>
            <div class="am-cf"></div>
        </div>
	<?php echo $form->end();?>
	</div>
</div>

<!-- /Main End-->
<script type="text/javascript">
	window.onload=function(){
		document.getElementById("tablelist").style.display="block";
	};
	
	//批量删除
	function removeAll()
	{
		var ck=document.getElementsByName('checkboxes[]');
		var j=0;
		for(var i=0;i<=parseInt(ck.length)-1;i++)
		{
			if(ck[i].checked)
			{
				j++;
			}
		}
		if(j>=1){
			if(confirm(j_confirm_delete))
			{
				document.UserRankForm.submit();
			}
		}
	}
</script>