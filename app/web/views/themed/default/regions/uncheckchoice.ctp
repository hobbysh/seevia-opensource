<?php 
/*****************************************************************************
 * Seevia 选择区域
 *===========================================================================
 * 版权所有上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 *---------------------------------------------------------------------------
 *这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *不允许对程序代码以任何形式任何目的的再发布。
 *===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
ob_start();?>
<?php foreach($regions_selects as $k=>$r){?>
		<?php if(isset($r['select']) && sizeof($r['select']) == 2){?>
			<?php foreach($r['select'] as $kk=>$vv){?>
				<?php $r['default'] = $kk;?>
			<?php }?>
	<?php }?>
<?php if(isset($updateaddress_id)){?>
 <?php echo $form->select('Address.RegionUpdate.'.$ii.".".$k.$updateaddress_id,$r['select'],$r['default'],array("onchange"=>"reload_edit_uncheck_regions($updateaddress_id)",'empty'=>false),false); ?>
<?php }else{?>
 <?php echo $form->select('Address.RegionUpdate.'.$ii.".".$k,$r['select'],$r['default'],array("onchange"=>"reload_uncheck_regions($ii)",'empty'=>false),false); ?>
<?php }?>
<?php }echo "<em><font class='region_msg' color='red'></font><font></font></em>";?>
<?php 
$result['type']=0;
$result['message'] = ob_get_contents();
if(isset($updateaddress_id)){
	$result['updateaddress_id']=$updateaddress_id;
}
ob_end_clean();
echo json_encode($result);
?>