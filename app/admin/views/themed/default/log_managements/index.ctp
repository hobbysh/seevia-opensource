<style>
  
     .min_w{min-width:88px;}
     .am-btn{width:91px;}
</style>
<div class="am-u-md-12 am-u-sm-12">
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th class="thtype"><span><?php echo $ld['type_name']?></span></th>
				<th class="thtype"><?php echo $ld['success_for_today']?></th>
				<th class="thdate"><?php echo $ld['failure_number']?></th>
				<th class="thdate"><?php echo $ld['count']?></th>
				<th style="width:300px;"><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($sms_count)){ ?>
			<tr>
				<td class="min_w"><?php echo $ld['sms_type'] ?></td>
				<td><?php echo $html->link($sms_success,"/sms/histories?date=".$start_time."&date2=".$end_time."&flag=0",array('escape' => false)) ?></td>
				<td><?php echo $html->link($sms_error,"/sms/histories?date=".$start_time."&date2=".$end_time."&flag=1",array('escape' => false)) ?></td>
				<td><?php echo $html->link($sms_count,"",array('escape' => false)); ?></td>
				<td> 
				<a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/sms/histories/'); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['views']; ?>
                    </a>
				
					<?php if($svshow->operator_privilege("sms_histories_clear")){?>
					 <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  " href="javascript:void(0);" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'/sms/clearall';}">
						<span class="am-icon-trash-o"></span> <?php echo $ld['clear_all']; ?>
					</a>
		
		
		
				<?php 	}?></td>
			</tr>
			<?php }else{?>
			<tr>
				<td><?php echo $ld['sms_type'] ?></td>
				<td><?php echo $html->link($sms_success,"/sms/histories?date=".$start_time."&date2=".$end_time."&flag=0",array('escape' => false)) ?></td>
				<td><?php echo $html->link($sms_error,"/sms/histories?date=".$start_time."&date2=".$end_time."&flag=1",array('escape' => false)) ?></td>
				<td><?php echo $html->link($sms_count,"",array('escape' => false)); ?></td>
				<td><?php echo $html->link($ld['views'],"",array('escape' => false,"class"=>"mt am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;&nbsp;';?>	<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/sms/histories/'); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['views']; ?>
                    </a>
				
				<?php if($svshow->operator_privilege("sms_histories_clear")){
	echo $html->link($ld['clear_all'],"/sms/clearall",array("class"=>"mt am-btn am-btn-danger am-btn-xs am-radius"),false,false);}?></td>
			</tr>
			<?php }?>
			<?php if(isset($web_count)&&$web_count>=0){?>
			<tr>
				<td><?php echo $ld['web_type'] ?></td>
				<td><?php echo $html->link($web_success,"/webservice_logs?start_time=".$start_time."&end_time=".$end_time."&status=1",array('escape' => false)) ?></td>
				<td><?php echo $html->link($web_error,"/webservice_logs?start_time=".$start_time."&end_time=".$end_time."&status=0",array('escape' => false)) ?></td>
				<td><?php echo $html->link($web_count,"/webservice_logs/",array('escape' => false)) ?></td>
				<td><?php echo $html->link($ld['views'],"/webservice_logs/",array('escape' => false,"class"=>"am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;&nbsp;';?><?php if($svshow->operator_privilege("webservice_logs_clear")){
	echo $html->link($ld['clear_all'],"/webservice_logs/clearall",array("class"=>"am-btn am-btn-danger am-btn-xs am-radius"),false,false);}?></td>
			</tr>
			<?php }?>
			<?php if(isset($tbs_count)&&$tbs_count>0){?>
			<tr>
				<td><?php echo $ld['tb_type'] ?></td>
				<td><?php echo $html->link($tb_success,"/taobao_update_logs?start_time=".$start_time."&end_time=".$end_time."&status=1",array('escape' => false)) ?></td>
				<td><?php echo $html->link($tb_error,"/taobao_update_logs?start_time=".$start_time."&end_time=".$end_time."&status=0",array('escape' => false)) ?></td>
				<td><?php echo $html->link($tb_count,"/taobao_update_logs/",array('escape' => false)); ?></td>
				<td> <a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn am-seevia-btn-view"  href="<?php echo $html->url('/taobao_update_logs/');?>">
				<span class="am-icon-eye"></span> <?php echo $ld['views']; ?>
				</a>
				
			 
		            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger " href="<?php echo $html->url('/taobao_update_logs/clearall');?>">
						<span class="am-icon-trash-o"></span> <?php echo $ld['clear_all']; ?>
						</a>
			</td>
			</tr>
			<?php }?>
			<?php if(isset($jds_count)&&$jds_count>0){?>
				<?php if(isset($jd_count)){?>
				<tr>
					<td><?php echo $ld['jd_type'] ?></td>
					<td><?php echo $html->link($jd_success,"/jingdong_update_logs?start_time=".$start_time."&end_time=".$end_time."&status=1",array('escape' => false)) ?></td>
					<td><?php echo $html->link($jd_error,"/jingdong_update_logs?start_time=".$start_time."&end_time=".$end_time."&status=0",array('escape' => false)) ?></td>
					<td><?php echo $html->link($jd_count,"/jingdong_update_logs/",array('escape' => false)) ?></td>
					<td>
					 
			      <a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/jingdong_update_logs/');?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['views']; ?>
                    </a>
		 <a class="mt am-btn am-btn-default am-btn-xs am-text-danger " href="<?php echo $html->url('/jingdong_update_logs/clearall');?>">
		 <span class="am-icon-trash-o"></span> <?php echo $ld['clear_all']; ?>
		 </a>
			        </td>
				</tr>	
				<?php }else{?>
				<tr>
					<td><?php echo $ld['jd_type'] ?></td>
					<td><?php echo $html->link($jd_success,"/jingdong_update_logs?start_time=".$start_time."&end_time=".$end_time."&status=1",array('escape' => false)) ?></td>
					<td><?php echo $html->link($jd_error,"/jingdong_update_logs?start_time=".$start_time."&end_time=".$end_time."&status=0",array('escape' => false)) ?></td>
					<td><?php echo $html->link($jd_count,"/jingdong_update_logs/",array('escape' => false)) ?></td>
					<td><?php echo $html->link($ld['views'],"",array('escape' => false,"class"=>"am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;&nbsp;';?><?php if($svshow->operator_privilege("jingdong_update_logs_clear")){
						
		echo $html->link($ld['clear_all'],"/jingdong_update_logs/clearall",array("class"=>"am-btn am-btn-danger am-btn-xs am-radius"),false,false);}?></td>
				</tr>
				<?php }?>
			<?php }?>
			<?php if(isset($mail_count)){?>	
			<tr>
				<td><?php echo $ld['mail_type'] ?></td>
				<td><?php echo $html->link($mail_success,"/mail_statistics?start_time=".$start_time."&end_time=".$end_time."",array('escape' => false)) ?></td>
				<td><?php echo $mail_error ?></td>
				<td><?php echo $html->link($mail_count,"/mail_statistics/",array('escape' => false)) ?></td>
<td>
				<!--?php 
				echo $html->link($ld['views'],"/mail_statistics/",array('escape' => false,"class"=>"am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;&nbsp;';?-->   
				<a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn am-seevia-btn-view" target='_blank' href="<?php echo '/mail_statistics/';?>">
				<span class="am-icon-eye"></span> <?php echo $ld['views']; ?>
				</a>
				<!--?php if($svshow->operator_privilege("mail_statistics_clear")){
				echo $html->link($ld['clear_all'],"/mail_statistics/clearall",array("class"=>"am-btn am-btn-danger am-btn-xs am-radius"),false,false);
				}?-->
	                 <a  class="mt am-btn am-btn-default am-btn-xs am-text-danger " href="/mail_statistics/clearall">
                        <span class="am-icon-trash-o"></span>  <?php echo $ld['clear_all']; ?>
                      </a>
		</td>
			</tr>
			<?php }?>
			<?php if(isset($wb_count)){?>		
			<tr>
				<td><?php echo $ld['wb_type'] ?></td>
				<td><?php echo $html->link($wb_success,"/weibo_op_logs?start_time=".$start_time."&end_time=".$end_time."&&error_msg=0",array('escape' => false)) ?></td>
				<td><?php echo $html->link($wb_error,"/weibo_op_logs?start_time=".$start_time."&end_time=".$end_time."&&error_msg=1",array('escape' => false)) ?></td>
				<td><?php echo $html->link($wb_count,"/weibo_op_logs/",array('escape' => false)) ?></td>
				<td><?php echo $html->link($ld['views'],"/weibo_op_logs/",array('escape' => false,"class"=>"am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;&nbsp;';?><?php if($svshow->operator_privilege("weibo_op_logs_clear")){
	echo $html->link($ld['clear_all'],"/weibo_op_logs/clearall",array("class"=>"am-btn am-btn-danger am-btn-xs am-radius"),false,false);}?></td>
			</tr>
			<?php }?>	
		</tbody>
	</table>
</div>	