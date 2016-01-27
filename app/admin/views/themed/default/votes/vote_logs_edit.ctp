<?php echo $form->create('votes',array('action'=>'/vote_logs_edit/'.$vote_logs_id));?>
<div class="content"> <?php echo $this->element('ur_here', array('cache'=>'+0 hour'));?> <br />
	<p class="add_categories"><strong><?php echo $html->link($html->image('add.gif',array('class'=>'vmiddle'))."在线调查日志列表","vote_logs/",'',false,false);?></strong></p>
	<div class="home_main" id="guides_1">
		<div class="order_stat athe_infos configvalues">
			<div class="title">
				<h1> <?php echo $html->image('tab_left.gif',array('class'=>'left'))?> <?php echo $html->image('tab_right.gif',array('class'=>'right'))?> 在线调查日志编辑</h1>
			</div>
			<div class="box">
				<div class="shop_config menus_configs guides" style="width:500px;"> <br />
					<dl>
						<dt style="width:100px;">用户名： </dt>
						<dd><?php echo @$new_user_list[$vote_logs_list["VoteLog"]["user_id"]]?></dd>
					</dl>
					<dl>
						<dt style="width:100px;">投票主题： </dt>
						<dd><?php echo @$new_vote_list[$vote_logs_list["VoteLog"]["vote_id"]]?></dd>
					</dl>
					<dl>
						<dt style="width:100px;">投票的ip地址： </dt>
						<dd><?php echo @$vote_logs_list["VoteLog"]["ip_address"]?></dd>
					</dl>
					<dl>
						<dt style="width:100px;">用户所用的操作系统： </dt>
						<dd><?php echo @$vote_logs_list["VoteLog"]["system"]?></dd>
					</dl>
					<dl>
						<dt style="width:100px;">用户所用的浏览器： </dt>
						<dd><?php echo @$vote_logs_list["VoteLog"]["browser"]?></dd>
					</dl>
					<dl>
						<dt style="width:100px;">用户所用的分辨率： </dt>
						<dd><?php echo @$vote_logs_list["VoteLog"]["resolution"]?></dd>
					</dl>
					<dl>
						<dt style="width:100px;">用户所选值： </dt>
						<dd>
							<?php if(isset($vote_logs_list['VoteLog']['vote_option_id_arr']) && sizeof($vote_logs_list['VoteLog']['vote_option_id_arr'])>0){?>
							<?php foreach($vote_logs_list['VoteLog']['vote_option_id_arr'] as $vv){
			echo @$new_voteoption_list[$vv]."<br />";
		}}?>
						</dd>
					</dl>
					<dl>
						<dt style="width:100px;">前台是否显示： </dt>
						<dd>
							<input type="radio"  name="data[VoteLog][status]" value="1" <?php if($vote_logs_list['VoteLog']['status']==1){ echo "checked";}?>  />
							是
							<input type="radio" name="data[VoteLog][status]" value="0" <?php if($vote_logs_list['VoteLog']['status']==0){ echo "checked";}?> />
							否 </dd>
					</dl>
				</div>
			</div>
			<p class="submit_btn">
				<input type="submit" value="确定"  />
				<input type="reset" value="重置"  />
			</p>
		</div>
	</div>
	<?php echo $form->end();?> </div>
