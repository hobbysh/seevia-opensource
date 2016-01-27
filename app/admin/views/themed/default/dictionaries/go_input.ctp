<?php 
/*****************************************************************************
 * SV-Cart 字典内容管理
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
<?php if ($result['type'] == 0  && $result['change_type'] == 'input'){?>
<input name="update" value="<?php echo $result['value']?>" style="width:130px;margin:-5px 0"  id="input" onblur="update_lang_dictionarie(this,<?php echo $result['id']?>,'<?php echo $result['style']?>',<?php echo $result['len']?>);">
<?php }?>
<?php $result['message'] = ob_get_contents();
ob_end_clean();?>
<?php ob_start();?>
<?php if ($result['type'] == 0 && $result['change_type'] == 'select'){?>
<?php if(isset($result['language_type']) && count($result['language_type'])>0){?>
		 	<select id="select" name="language_type" style="width:90px;margin:-5px 0" onblur="update_lang_dictionarie(this,<?php echo $result['id']?>,'<?php echo $result['style']?>',<?php echo $result['len']?>);">
    		<?php foreach($result['language_type'] as $key=>$value){ ?>
    		<?php if($value['SystemResource']['resource_value'] != ""){?>
    		<?php if(isset($result['value']) && $result['value'] == $value['SystemResource']['resource_value']){?>
    		<option selected>
    		<?php }else{?>
    		<option>
			<?php }?>
    		<?php echo $value['SystemResourceI18n']['name'];?></option>
    		<?php }?>
    		<?php }?>
    		</select>
    		<?php }else{?>
    		无类型选择
    		<?php }?>
<?php }?>
<?php $result['type_message'] = ob_get_contents();
ob_end_clean();
echo json_encode($result);
?>