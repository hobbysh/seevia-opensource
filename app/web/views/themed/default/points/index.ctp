<div class="am-cf am-user">
	<h3><?php echo $ld['account_reward_points'] ?></h3>
</div>
<div class="am-u-user-point">
    <div class="am-g">
        <div class="am-fl am-text-right"><strong><?php echo $ld["your_points"]; ?></strong></div>
        <div class="am-u-lg-2 am-u-md-8 am-u-sm-6 am-text-left"><?php echo $my_point; ?></div>
    </div>
    <div class="am-point-log">
        <h4 style="margin-top:8px;color:#0e90d2;"><?php echo $ld['details_enquiry_points'] ?></h4>
        <?php echo $form->create('',array('action'=>'/','type'=>'get','name'=>"SearchForm","class"=>'am-form am-form-horizontal'));?>
        <div class="am-form-detail">
            <ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
                <li>
                    <label class="am-fl am-form-label"><?php echo $ld['query_time']?></label>
                    <div class="am-u-lg-4 am-u-md-6 am-u-sm-12"><select name="date" data-am-selected>
					    <option value="0"><?php echo $ld["records_of_all_points"]?></option>
					    <option value="3" <?php if(isset($date) && $date==3){echo "selected";}?>><?php echo $ld['record_points_three_months']?></option>
				        </select>
                    </div>
                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-12"><input type="submit" class="am-btn am-btn-primary am-btn-sm am-radius"  value="<?php echo $ld['query'];?>"/></div>
                </li>
            </ul>
        </div>
        <?php echo $form->end();?>
        <table class="am-table">
            <thead>
        		<tr>
        			<th width="25%"><?php echo $ld['points_date']?></th>
        			<th width="20%"><?php echo $ld['type']?></th>
        			<th width="20%"><?php echo $ld["integral"]?></th>
        			<th><?php echo $ld['help']?></th>
        		</tr>
            </thead>
            <tbody>
        		<?php if(isset($my_point_logs)&&sizeof($my_point_logs)>0){foreach($my_point_logs as $k=>$v){?>
        		<tr>
        			<td><?php echo date("Y-m-d",strtotime($v['UserPointLog']['created']));?></td>
        			<td><?php echo $log_type[$v['UserPointLog']['log_type']];?></td>
        			<td><?php echo $v['UserPointLog']['point']?></td>
        			<td><?php echo $v['UserPointLog']['system_note']?></td>
        		</tr>
        		<?php }}else{?>
                <tr><td><?php echo $ld['no_details_enquiry_points']; ?></td></tr>
                <?php } ?>
            </tbody>
    	</table>
        <div class="pagenum"><?php echo $this->element('pager');?></div>
    </div>
</div>
