<?php 
/*****************************************************************************
 * SV-Cart �����ֵ����
 * ===========================================================================
 * ��Ȩ���� �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
ob_start();?>
<?php echo $javascript->link('foreach_translate');?>
<?php if ($result['type'] == 0){?>

	<div id="<?php echo $result['style']?><?php echo $result['id']?>" onclick="javascript:go_input(<?php echo $result['id']?>,'<?php echo $result['value']?>','<?php echo $result['style']?>',<?php echo $result['len']?>);">
	<?php echo $result['value']?>
	</div>

<?php }else{?>
<?php echo $result['message'];?>
<?php }?>
<?php $result['message'] = ob_get_contents();
ob_end_clean();
echo json_encode($result);
?>