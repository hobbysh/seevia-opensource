<?php 
	//echo $ad_show_code;
//	pr($advertisement_lists);die;
	//pr($this->data['advertisement_list'][$ad_show_code]);
	//die;
	?>
<?php 

if(isset($advertisement_lists[$ad_show_code])){
//	foreach ($advertisement_lists[$ad_show_code] as $k=>$v){
//	    //if(isset($v['AdvertisementEffect']['stauts'])&&$v['AdvertisementEffect']['stauts']==1){}
//		$advertisement_lists[$ad_show_code][$k]['AdvertisementEffect']['images']=(array)json_decode($v['AdvertisementEffect']['images']);
//		$advertisement_lists[$ad_show_code][$k]['AdvertisementEffect']['configs']=(array)json_decode($v['AdvertisementEffect']['configs']);
//		foreach($advertisement_lists[$ad_show_code][$k]['AdvertisementEffect']['images'] as $kk=>$vv){
//			$advertisement_lists[$ad_show_code][$k]['AdvertisementEffect']['images'][$kk]=(array)$vv;
//		}
//	}
//	pr($advertisement_lists[$ad_show_code]);die;
//echo "a";
	foreach ($advertisement_lists[$ad_show_code] as $kk=>$vv){
      if(!empty($vv['AdvertisementEffect']['type']) &&$vv['AdvertisementEffect']['status']==1){
		 echo $this->element($vv['AdvertisementEffect']['type'],array("effect"=>$vv['AdvertisementEffect']));
      }else{
      	  if(isset($vv['AdvertisementI18n']['code']))
      	  	  if(isset($lable_header) && isset($lable_footer)){
      	  	  	echo $lable_header.$vv['AdvertisementI18n']['code'].$lable_footer;
      	  	  }else{
      	  	  	echo $vv['AdvertisementI18n']['code'];
      	  	  }
      }
	}
} 
//if(isset($this->data['advertisement_list'][$ad_show_code])){
//	foreach ($this->data['advertisement_list'][$ad_show_code] as $k=>$v){
//	    //if(isset($v['AdvertisementEffect']['stauts'])&&$v['AdvertisementEffect']['stauts']==1){}
//		$this->data['advertisement_list'][$ad_show_code][$k]['AdvertisementEffect']['images']=(array)json_decode($v['AdvertisementEffect']['images']);
//		$this->data['advertisement_list'][$ad_show_code][$k]['AdvertisementEffect']['configs']=(array)json_decode($v['AdvertisementEffect']['configs']);
//		foreach($this->data['advertisement_list'][$ad_show_code][$k]['AdvertisementEffect']['images'] as $kk=>$vv){
//			$this->data['advertisement_list'][$ad_show_code][$k]['AdvertisementEffect']['images'][$kk]=(array)$vv;
//		}
//	}
////	pr($this->data['advertisement_list'][$ad_show_code]);die;
//	foreach ($this->data['advertisement_list'][$ad_show_code] as $kk=>$vv){
//      if(!empty($vv['AdvertisementEffect']['type']) &&$vv['AdvertisementEffect']['status']==1){
//		 echo $this->element($vv['AdvertisementEffect']['type'],array("effect"=>$vv['AdvertisementEffect']));
//      }else{
//      	  if(isset($vv['AdvertisementI18n']['code']))
//      	  	  if(isset($lable_header) && isset($lable_footer)){
//      	  	  	echo $lable_header.$vv['AdvertisementI18n']['code'].$lable_footer;
//      	  	  }else{
//      	  	  	echo $vv['AdvertisementI18n']['code'];
//      	  	  }
//      }
//	}
//} 
?>
<?php //pr($this->data['advertisement_list'][$ad_show_code]);bxslider?>