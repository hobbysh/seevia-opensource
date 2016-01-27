<?php 
/*****************************************************************************
 * SV-Cart 更新字典管理
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