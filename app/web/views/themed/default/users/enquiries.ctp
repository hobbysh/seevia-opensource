<div class="am-cf am-user">
	<h3><?php echo $ld['enquiry'] ?></h3>
</div>
<div class="am-u-ser-enquiry">
<?php if(isset($enquiries_list)&&sizeof($enquiries_list)>0){ ?>
  <table class="am-table am-table-bd am-table-striped am-table-hover">
	<tr class="am-active">
	  <th width="36%"><?php echo $ld['product_name']?></th>
	  <th width="20%" class="am-hide-sm-only"><?php echo $ld['attribute']?></th>
	  <th width="10%"><?php echo $ld['price']?></th>
	  <th width="8%"><?php echo $ld['quantity']?></th>
	  <th width="10%"><?php echo $ld['status']?></th>
	  <th width="20%" class="am-hide-sm-only"><?php echo $ld['submit_time']?></th>
	</tr>
	<?php foreach($enquiries_list as $k=>$v){ ?>
	<tr>
	  <td>
		<?php 
			$sku_code=$v['Enquiry']['part_num']; 
			$sku_code_arr=split(';',$v['Enquiry']['part_num']);
			if(sizeof($sku_code_arr)>1){
				foreach($sku_code_arr as $kk=>$vv){
					echo isset($product_code_list[$vv])?"<a href='".$html->url('/products/'.$product_id_list[$vv])."' target='_blank'>".$product_code_list[$vv]."</a><br>":'&nbsp;&nbsp;';
				}
			}else{
				echo isset($product_code_list[$sku_code])?"<a href='".$html->url('/products/'.$product_id_list[$sku_code])."' target='_blank'>".$product_code_list[$sku_code]."</a>":'&nbsp;&nbsp;';
			}
		?>
	  </td>
	  <td  class="am-hide-sm-only">
		<?php 
			$attribute=$v['Enquiry']['attribute']; 
			$attribute_arr=split(';',$v['Enquiry']['attribute']);
			if(sizeof($attribute_arr)>1){
				foreach($attribute_arr as $kk=>$vv){
					echo isset($vv)&&!empty($vv)?$vv."<br>":'&nbsp;&nbsp;<br>';
				}
			}else{
				echo $v['Enquiry']['attribute'];
			}
		?>
		<?php //echo $v['Enquiry']['attribute'];?>
	  </td>
	  <td>
		<?php 
			$price=$v['Enquiry']['target_price']; 
			$price_arr=split(';',$v['Enquiry']['target_price']);
			if(sizeof($price_arr)>1){
				foreach($price_arr as $kk=>$vv){
					echo isset($vv)&&!empty($vv)?$vv."<br>":'&nbsp;&nbsp;<br>';
				}
			}else{
				echo $v['Enquiry']['target_price'];
			}
		?>
	  </td>
	  <td>
		<?php 
			$qty=$v['Enquiry']['qty']; 
			$qty_arr=split(';',$v['Enquiry']['qty']);
			if(sizeof($qty_arr)>1){
				foreach($qty_arr as $kk=>$vv){
					echo isset($vv)&&!empty($vv)?$vv."<br>":'&nbsp;&nbsp;<br>';
				}
			}else{
				echo $v['Enquiry']['qty'];
			}
		?>
	  </td>
	  <td><?php
	    	switch($v['Enquiry']['status']){
			  case 0:
			    echo $ld['unrecognized'];
				break;
			  case 1:
				echo $ld['confirmed'];
				break;
			  case 2:
				echo $ld['canceled'];
				break;
			  case 3:
				echo $ld['complete'];
				break;
			} 
			?>
      </td>
	  <td  class="am-hide-sm-only"><?php echo $v['Enquiry']['created']; ?></td>
	</tr>
	<?php }?>
  </table>
  <?php echo $this->element('pager'); ?>
  <?php }else{?>
	<span style="margin-left:10px;"><?php echo $ld['no_record'];?></span>
  <?php }?>
</div>
