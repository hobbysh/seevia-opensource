<?php 
/*****************************************************************************
 * SV-Cart ѡ������
 *===========================================================================
 * ��Ȩ�����Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 *---------------------------------------------------------------------------
 *�ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 *������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 *===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
ob_start();?>
<?php foreach($regions_selects as $k=>$r){?>
		<?php if(isset($r['select']) && sizeof($r['select']) == 2){?>
			<?php foreach($r['select'] as $kk=>$vv){?>
				<?php $r['default'] = $kk;?>
			<?php }?>
	<?php }?>	
	
<?php if(isset($address_id)){?>
 <?php echo $form->select('Address.Region.'.$k.$address_id,$r['select'],$r['default'],array("onchange"=>"reload_edit_regions($address_id)"),false); ?>
<?php }else{?>
 <?php echo $form->select('Address.Region.'.$k,$r['select'],$r['default'],array("onchange"=>"reload_regions()"),false); ?>
<?php }?>
<?php }?>
<?php 
$result['type']=0;
$result['message'] = ob_get_contents();
if(isset($address_id)){
	$result['address_id']=$address_id;
}
ob_end_clean();
echo json_encode($result);
?>