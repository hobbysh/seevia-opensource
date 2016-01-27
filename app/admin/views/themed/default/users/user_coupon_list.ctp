<div id="user_coupon_list_ajaxdata">
	<p style="text-align:right;" class="action-span">
	<a href="javascript:void(0);" class="am-btn am-btn-warning am-radius am-btn-sm" data-am-modal="{target: '#user-coupon-popup',closeViaDimmer: 0,width:600, height:400}" onclick="sendcoupon(<?php echo $user_id; ?>)"><?php echo $ld['rebate_011'] ?></a></p>
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th><?php echo $ld['coupon_name']?></th>
				<th><?php echo $ld['coupon_no']?></th>
				<th><?php echo $ld['type'] ?></th>
				<th><?php echo $ld['rebate_005'] ?></th>
				<th><?php echo $ld['rebate_006'] ?></th>
				<th><?php echo $ld['rebate_026'] ?></th>
				<th><?php echo $ld['rebate_027'] ?></th>
				<th><?php echo $ld['use'].$ld['date'] ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($coupon_list)&&sizeof($coupon_list)>0){foreach($coupon_list as $v){ ?>
			<tr>
				<td><?php echo $v['CouponTypeI18n']['name']?></td>
				<td><?php echo $v['Coupon']['sn_code']?></td>
				<td><?php echo isset($coupontype[$v['CouponType']['send_type']])?$coupontype[$v['CouponType']['send_type']]:$v['CouponType']['send_type']; ?>(<?php echo $v['CouponType']['type']=='1'?$ld['discount']:$ld['relief'] ?>)</td>
				<td><?php echo $v['CouponType']['money'] ?></td>
				<td><?php echo $v['CouponType']['min_amount'] ?></td>
				<td><?php echo date("Y-m-d",strtotime($v['CouponType']['use_start_date'])); ?></td>
				<td><?php echo date("Y-m-d",strtotime($v['CouponType']['use_end_date'])); ?></td>
				<td><?php echo strtotime($v['Coupon']['used_time'])>strtotime($v['CouponType']['created'])?$v['Coupon']['used_time']:'' ?></td>
			</tr>
			<?php }} ?>
		</tbody>
	</table>
</div>
<div class="am-modal am-modal-no-btn" id="user-coupon-popup">
	<div class="am-modal-dialog">
		<div class="am-modal-hd"><?php echo $ld['users_user_address'];?>
		<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
	</div>
    <div class="am-modal-bd" id="send_coupon_list"></div>
  </div>
</div>
<script type="text/javascript">
function sendcoupon(user_id){
	$.ajax({url: "/admin/coupons/send_coupon_to_user/"+user_id,
			type:"POST",
			data:{},
			dataType:"html",
			success: function(data){
				try{
					$("#send_coupon_list").html(data);
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
}
</script>