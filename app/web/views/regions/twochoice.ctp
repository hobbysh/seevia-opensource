<?php
/*****************************************************************************
 * SV-Cart 选择区域
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

<i>
<?php if($r['default'] && !empty($r['select'][$r['default']]))$str_arr[] = $r['select'][$r['default']]?>
<?php if($k==1)echo $ld['state_province'].":&nbsp;";?>
<!--省份/直辖市:&nbsp;-->
<?php if($k==2)echo $ld['city_county_district'].":";?>
<!--市/县/区:&nbsp;-->
</i>
<?php 
	if(isset($updateaddress_id)){
		if($check){
			echo $form->select('Address.RegionUpdate.'.$k.$updateaddress_id,$r['select'],$r['default'],array("onchange"=>"reload_edit_two_regions($updateaddress_id)","chkRules"=>"region:".$ld['please_select_a_region'],'empty'=>false,'name'=>'data[Address][RegionUpdate][' .$updateaddress_id . '][' . $k . ']'),false); 
		}else{
			echo $form->select('Address.RegionUpdate.'.$k.$updateaddress_id,$r['select'],$r['default'],array("onchange"=>"reload_edit_two_regions($updateaddress_id)",'empty'=>false,'name'=>'data[Address][RegionUpdate][' .$updateaddress_id . '][' . $k . ']'),false); 
		}
	}else{
		if($check){
			echo $form->select('Address.RegionUpdate.'.$k,$r['select'],$r['default'],array("onchange"=>"reload_two_regions()","chkRules"=>"region:".$ld['please_select_a_region'],'empty'=>false),false); 
		}else{
			echo $form->select('Address.RegionUpdate.'.$k,$r['select'],$r['default'],array("onchange"=>"reload_two_regions()",'empty'=>false),false); 	
		}
	}
?>
<?php }?> 
<?php 
$result['type']=0;
$result['message'] = ob_get_contents();
if(isset($updateaddress_id)) {
	$result['updateaddress_id']=$updateaddress_id;
}
ob_end_clean();
if(!empty($str_arr)){
		if(count($str_arr)>1&&(LOCALE=="eng")){
			$result['str'] = implode('，',array_reverse($str_arr)).'，';	
		}else{
			$result['str'] = implode('，',$str_arr).'，';
		}
//	$result['str'] = implode('，',$str_arr).'，';
}else{
	$result['str'] = '';
}
echo json_encode($result);
?>