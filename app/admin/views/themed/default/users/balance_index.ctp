<style>.am-form-label{font-weight:bold; margin-top:-4px; left:20px;}</style><div class="listsearch">
    <?php echo $form->create('User',array('action'=>'/balance_index','name'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
    	
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label" style="padding-top:10px"><?php echo $ld['member_name']?></label>
            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7 " style="padding:0 0.5rem;">
                <input type="text" name="user_name" id="user_name" value="<?php echo @$user_name?>"/>
            </div>
        </li>
        
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:10px">Email</label>
            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7" style="padding:0 0.5rem;">
                <input type="text" name="user_email" value="<?php echo @$user_email?>" id="user_email"/>
            </div>
           
          </li>
           <li>
            <div class="am-u-lg-2  am-show-lg-only">
                <input class= "am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>" /> 
           </div>
       </li>
        <li style="margin:0 0 10px 0" class="am-hide-lg-only" >
        	<label class="am-u-md-4 am-u-sm-4 am-form-label"> </label>
        	<div class="am-u-md-7 am-u-sm-7 am-fl ">
    			<input class= "am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>" /> 
           </div>
        </li>
          
    </ul>
    	<a class="am-btn-default  am-btn am-radius am-btn-xs am-fr" onclick="sv_advanced_search(this,'advanced_user')"><?php echo $ld['advanced_search']?>
        </a>		
    <ul id="advanced_user" class="am-avg-sm-1 am-avg-md-1 am-avg-lg-1" style="display:none;margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-4 am-u-md-2 am-u-lg-1 am-form-label"><?php echo $ld['registration_time']?></label>
            <div class="am-u-sm-3 am-u-md-2 am-u-lg-1  am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <em class="am-fl" style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3 am-u-md-2 am-u-lg-1 am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="<?php echo $end_date;?>" />
            </div>
        </li>
       
    </ul>
    			
    <?php echo $form->end();?><!----->
</div>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['date']?></b></th>
            
            <th><?php echo $ld['member_name']?></th>
            <th><?php echo $ld['type']?></th>
            <th><?php echo $ld['amount']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['remarks_notes']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($log_list) && sizeof($log_list)>0){foreach($log_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['UserBalanceLog']['id']?>" /></span><?php echo $v['UserBalanceLog']['created']?></td>
                
                <td><?php echo $v['User']['name']?></td>
                <td><?php
                    if($v['UserBalanceLog']['log_type']=='A'){
                        echo $ld['operate'];
                    }elseif($v['UserBalanceLog']['log_type']=='O'){
                        echo $ld['single'];
                    }elseif($v['UserBalanceLog']['log_type']=='B'){
                        echo $v['UserBalanceLog']['amount']>0?$ld['supply']:$ld['deduction'];
                    }elseif($v['UserBalanceLog']['log_type']=='R'){
                        echo $ld['refund'];
                    }
                    ?></td>
                <td><?php
                    if($v['UserBalanceLog']['amount']>0)
                        echo '+'.$v['UserBalanceLog']['amount'];
                    else
                        echo $v['UserBalanceLog']['amount'];
                    ?></td>
                <td class="thwrap am-hide-md-down"><span><?php echo $v['UserBalanceLog']['system_note']?></span></td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php
    if($svshow->operator_privilege("balance_log_remove")){
        if(isset($log_list) && sizeof($log_list)){
            ?>
            <div id="btnouterlist" class="btnouterlist" style="margin-left:2px;">
                <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-hide-sm-down ">
                    <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
                    <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['batch_delete']?>" onclick="batch_logs()" />
                </div>
                <div class="am-u-lg-9 am-u-md-7 am-u-sm-12"><?php echo $this->element('pagers')?></div>
                <div class="am-cf"></div>
            </div>
        <?php
        }	}
    ?>  
</div>
    <script  >
    	 function batch_logs(){
    	 	   	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	             var postData = "";
               	for(var i=0;i<bratch_operat_check.length;i++){
			      if(bratch_operat_check[i].checked){  
			      postData+="&checkboxes[]="+bratch_operat_check[i].value;
	      	     	}
				}
      
	    $.ajax({
		url:admin_webroot+"users/batch_logs/",
		type:'POST',
		data:postData,
		datatype:'json',
             success:function(data){ 
           window.location.href=window.location.href;
             } 
	    });
	    	 
     
     }
 </script>	
		
		