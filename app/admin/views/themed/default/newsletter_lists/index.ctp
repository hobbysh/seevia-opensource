<style type="text/css">
.am-form-label{font-weight:bold;text-align:right;margin-left:20px;}
</style>
<div class="listsearch"> <?php echo $form->create('',array('action'=>'/','type'=>'get','name'=>"SearchForm",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['email']?></label>
            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7" style="padding:0 0.5rem;">
                <input type="text" name="email"value="<?php echo @$email?>" id="email"/>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['registration_time']?></label>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date" value="<?php echo @$date;?>" />
            </div>
            <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;">-</em>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php echo @$date2;;?>" />
            </div>
        </li>
        	<li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_group']?></label>
            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7" style="padding:0 0.5rem;">
                <select name="group_id" id="group_id" data-am-selected ="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value="" ><?php echo $ld['all_data']?></option>
                    <?php if(isset($group_list) && sizeof($group_list)>0){foreach($group_list as $gk=>$gv){?>
                        <option value="<?php echo $gk;?>" <?php if(isset($group_id) && $group_id==$gk){echo "selected";}?> ><?php echo $gv;?></option>
                    <?php }}?>
                </select>
            </div>
        </li>	
     <li style="margin:0 0 10px 0">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"> </label>
		 <div class="am-u-lg-7 am-u-md-7 am-u-sm-7 " style="padding:0 0.5rem;">
	       	<input  style="margin:0px 9px 4px 0px " class="am-btn am-btn-success am-radius am-btn-sm  " type="submit" value="<?php echo $ld['search'];?>" /><b class="am-fr">&nbsp;</b>
			<input  style="margin:0px 9px 4px 0px " class="am-btn am-btn-success am-radius am-btn-sm   " type="button" class="big" value="<?php echo $ld['export']?>" onclick="newsletter_list_export()"/>
		</div>	

   
        </li>   
     </ul>
    <?php echo $form->end();?>
</div>
					
<p class="am-u-md-12 am-text-right am-btn-group-xs">
            <?php 
    		if($svshow->operator_privilege("user_groups_view")){echo $html->link($ld['user_group'],'/user_groups/',array("class"=>"am-btn am-radius am-btn-sm am-btn-default "),false,false);}echo"&nbsp;";
    	      if($svshow->operator_privilege('users_add')){echo $html->link($ld['batch_upload_user'],'/newsletter_lists/uploadusers',array("class"=>"am-btn am-btn-default am-radius am-btn-sm "),false,false);}?>
    <?php if($svshow->operator_privilege("users_add")){ ?>
      <a class='am-btn am-btn-warning am-btn-sm am-radius' href="<?php echo $html->url('/newsletter_lists/view'); ?>">
    			<span class='am-icon-plus' ></span><?php echo $ld['add_user'] ;?>
    			</a>
    	<?php }?>
    	      	  
</p>
	
<?php echo $form->create('',array('action'=>'/',"name"=>"ProForm","type"=>"POST",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap "><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['email']?></b></label></th>
          
            <th><?php echo $ld['mobile'];?></th>
            <th class="thwrap am-hide-sm-down"><?php echo $ld['user_group'];?></th>
            <th class="thwrap am-hide-sm-down"><?php echo $ld['registration_time']?></th>
            <th><?php echo $ld['status']?></th>
            <th  ><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($newsletterlist_data) && sizeof($newsletterlist_data)>0){?>
            <?php foreach( $newsletterlist_data as $k=>$v ){ ?>
                <tr>
                    <td class="thwrap "><label style="margin:0 0 0 0;" class="am-checkbox am-success">
                    <span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v["NewsletterList"]["id"]?>" /></span><?php echo $v["NewsletterList"]["email"]?></td>
                    
                    <td><?php echo $v["NewsletterList"]["mobile"]?></td>
                    <td class="thwrap am-hide-sm-down"><?php echo isset($v["NewsletterList"]["group"])?$v["NewsletterList"]["group"]:"";?></td>
                    <td class="thwrap am-hide-sm-down"><?php echo $v["NewsletterList"]["created"]?></td>
                    <td>
                        <?php if ($v['NewsletterList']['status'] == 1){?>
                            <div style="color:#5eb95e" class="am-icon-check"></div>
                        <?php }elseif($v['NewsletterList']['status'] == 2){?>
                            <div style="color:#dd514c" class="am-icon-close"></div>
                        <?php }?>
                    </td>
                    <td class="am-action"><?php if($svshow->operator_privilege("newsletter_lists_status")){?>
                            <?php if($v['NewsletterList']['status'] == 1){?>
                                <?php echo $html->link($ld['unsubscribe'],"/newsletter_lists/unsubscribe/{$v['NewsletterList']['id']}",array("class"=>"am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;';?>
                            <?php }else{?>
                                <?php echo $html->link($ld['confirm'],"/newsletter_lists/confirm/{$v['NewsletterList']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs am-radius"));?>
                            <?php }?>
                        <?php }?>
                        <?php if($svshow->operator_privilege("newsletter_lists_edit")){?>
                                <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/newsletter_lists/view/'.$v['NewsletterList']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                       <?php  }?>
                        <?php if($svshow->operator_privilege("newsletter_lists_remove")){?> <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'/newsletter_lists/remove/<?php echo $v['NewsletterList']['id']?>';}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                        <?php }?></td>
                </tr>
            <?php }}else{?>
            <tr>
                <td  colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if($svshow->operator_privilege("newsletter_lists_status") && $svshow->operator_privilege("newsletter_lists_remove")){?>
        <?php if(isset($newsletterlist_data) && sizeof($newsletterlist_data)){?>
            <div id="btnouterlist" class="btnouterlist">
            	<div class="am-u-lg-5 am-u-md-6 am-u-sm-12   am-hide-sm-down"style="margin-left:-2px;">
            	
            	    <div class="am-u-lg-2 am-u-md-3  am-fl"><label style="margin-right:5px;float:left; margin-top:6px;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label></div>
	                <div class=" am-u-lg-5 am-u-md-6  am-fl"><select  id="barch_opration_select" data-am-selected onchange="barch_opration_select_onchange(this)">
	                	<option value="unsubscribe"><?php echo $ld['bulk_unsubscribe']?></option>
	                    <option value="confirm"><?php echo $ld['batch_confirm']?></option>
	                    <option value="remove"><?php echo $ld['batch_delete']?></option>
	                  </select>
	                  </div>
                       <div class=" am-u-lg-5 am-u-md-3   am-fl"><input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" onclick="diachange(this);" value="<?php echo $ld['submit']?>" id="act_type" name="act_type" />
                      </div>
                    	
                  </div>
                <div  class="am-u-lg-7 am-u-md-6 am-u-sm-12">
                 <?php echo $this->element('pagers')?> 
                </div>
                <div class="am-cf"></div>
               </div>
        <?php }?>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function diachange(obj){
        var thisstatus=document.getElementById('act_type').value;
        var id=document.getElementsByName('checkboxes[]');
        var j=0;
        var image="";
        for( i=0;i<=parseInt(id.length)-1;i++ ){
            if(id[i].checked){
                j++;
            }
        }
        if( j>=1 ){
            if(confirm('<?php echo $ld['submit'] ?>'+obj.value+'?')){
                batch_change_status(thisstatus);
            }
        }else{
            alert("<?php echo $ld['please_select']?>");
        }
    }

    function batch_change_status(status){
        var id=document.getElementsByName('checkboxes[]');
        var i;
        var j=0;
        for( i=0;i<=parseInt(id.length)-1;i++ ){
            if(id[i].checked){
                j++;
            }
        }
        if( j<1 ){
            return false;
        }else{
            document.ProForm.action=admin_webroot+"newsletter_lists/change_status/"+status+"/";
            document.ProForm.onsubmit= "";
            document.ProForm.submit();
        }
    }

    function newsletter_list_export(){
        var email=document.getElementById("email").value;
        var date=document.getElementsByName("date")[0].value;
        if("undefined"==typeof date ){
            date="";
        }
        var date2=document.getElementsByName("date2")[0].value;
        if(typeof(date2)=="undefined"){
            date2="";
        }
        var mystatus=document.getElementById("mystatus").value;
        window.location.href = admin_webroot+"newsletter_lists/export/?mystatus="+mystatus+"&email="+email+"&date="+date+"&date2="+date2;
    }
</script>