<?php ob_start(); ?>
	
<?php echo $messege_error;?>

<?php 
$out1 = ob_get_contents();ob_end_clean();  
	$result=array("result"=>$out1,"message"=>$messege_error,"back_url"=>$back_url,"error_no"=>$error_no,"user_name"=>(isset($user_name)?$user_name:""),"user_rank"=>(isset($user_rank)?$user_rank:0),'user_data'=>isset($user_data)?$user_data:null);
	echo json_encode($result);?>