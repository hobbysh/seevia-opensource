 <style type="text/css">
 .am-form-label{font-weight:bold; text-align:center; margin-top:-5px;margin-left:20px;}
</style>
<div class="listsearch">
    <?php echo $form->create('',array('action'=>'/',"type"=>"get",'name'=>"SearchForm",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <?php if(!empty($Resource_info['contact_us_type'])){ ?>
    
        <li style="margin:0 0 10px 0"> 
                <label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label"><?php echo $ld['type']?></label>
                <div class="am-u-sm-7  am-u-md-7 am-u-lg-7">
                    <select name="contact_us_type" data-am-selected>
                        <option value=" "><?php echo $ld['all_data']; ?></option>
                        <?php foreach($Resource_info['contact_us_type'] as $k=>$v){ ?><option value="<?php echo $k; ?>" <?php echo isset($contact_us_type)&&$contact_us_type==$k?'selected':''; ?>><?php echo $v; ?></option><?php } ?>
                    </select>
                </div>
         </li>
        <?php } ?>
        				
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-md-3  am-u-lg-3 am-form-label"><?php echo $ld['added_time']?></label>
            <div class="am-u-sm-3 am-u-md-3 am-u-lg-3" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date" value="<?php echo @$date;?>" />
            </div>
            <em class="am-fl am-u-sm-1 am-u-md-1 am-text-center" style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php echo @$date2;?>" />
            </div>
        </li>
        		
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-md-3 am-u-lg-3  am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-8">
                <input placeholder="<?php echo $ld['company_name']?>/<?php echo $ld['contacter']?>/<?php echo $ld['email']?>/<?php echo $ld['order_web']?>/<?php echo "预约时间";?>" type="text" name="kword_name" value="<?php echo @$kword_name;?>"/>
            </div>
       </li>
       		
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-md-3  am-u-lg-3  am-form-label"> </label>
            <div class="am-u-sm-7 am-u-md-7  am-u-lg-5 ">
                <input class="am-btn am-btn-success am-radius am-btn-sm search_article" type="submit" value="<?php echo $ld['search']?>" />
            </div>
        </li>
        	
    </ul>
    <?php echo $form->end();?>
</div>
<?php echo $form->create('',array("action"=>"/batch",'name'=>"DeleteForm","type"=>"get",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
	    <tr>
	        <th style="max-width:200px;word-wrap:break-word;word-break:normal;"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['company_name']?>/<?php echo $ld['company_type']?></b></label></th>
	        <?php if(!empty($Resource_info['contact_us_type'])){ ?>
	         <th class="am-hide-md-down"><?php echo $ld['type']?></th>
	        <?php } ?> 
		        <th  ><?php echo $ld['contacter']?></th>
		        <th  class="am-hide-sm-down"><?php echo $ld['email']?>/<?php echo $ld['phone']?></th>
		        <th  class="am-hide-md-down" style="max-width:200px;"><?php echo $ld['order_web']?></th>
			 <th  class="am-hide-md-down"><?php echo $ld['contact_from']?></th>
			 <th  class="am-hide-md-down"><?php echo $ld['other']; ?></th>
			 <th  class="am-hide-md-down"><?php echo $ld['added_time']?></th>
			 <th  ><?php echo $ld['operate']?></th>
		</tr>
		</thead>
	<tbody>
<?php
if(isset($contact_info) && sizeof($contact_info)>0){
    foreach($contact_info as $k=>$v){
        ?>
        <tr>
            <td style="max-width:200px;word-wrap:break-word;word-break:normal; "><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Contact']['id']?>" /></span><?php echo $v["Contact"]["company"]."&nbsp;"; ?><br /><?php echo $v["Contact"]["company_type"]."&nbsp;"; ?></label>
            </td>
            <?php if(!empty($Resource_info['contact_us_type'])){ ?>
            <td class="am-hide-md-down"><?php echo isset($Resource_info['contact_us_type'][$v['Contact']['contact_type']])?$Resource_info['contact_us_type'][$v['Contact']['contact_type']]:$v['Contact']['contact_type']; ?>
            </td><?php } ?>
            <td  ><?php echo $v["Contact"]["contact_name"]; ?></td>
            <td  class="am-hide-sm-down"><?php echo $v["Contact"]["email"]; ?><br /><?php echo $v["Contact"]["mobile"] ?></td>
            <td  class="am-hide-md-down" style="max-width:200px;word-wrap:break-word;word-break:normal;"><?php echo $v["Contact"]["company_url"]; ?></td>
            <td  class="am-hide-md-down"><?php echo $v["Contact"]["from"]; ?></td>
            <td  class="am-hide-md-down"><?php echo $v["Contact"]["parameter_01"]."<br>".$v["Contact"]["parameter_02"]; ?></td>
            <td  class="am-hide-md-down"><?php echo $v["Contact"]["created"]."<br>"; ?></td>
            <td  ><?php if($svshow->operator_privilege("contacts_detail")){ ?>
          
             <a  style="margin-top:5px;" class="am-btn am-btn-success am-btn-xs am-seevia-btn am-seevia-btn-view" href="<?php echo $html->url('/contacts/view/'.$v['Contact']['id']);?>"> <span class="am-icon-eye"></span> <?php echo$ld['details_view']; ?>
                    </a>
            	<?php }?>
               <?php  if($svshow->operator_privilege("contacts_remove")){?> <a style="margin-top:5px;" class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete ){list_delete_submit(admin_webroot+'contacts/remove/<?php echo $v['Contact']['id']?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
               <?php  }?></td>
        </tr>
    <?php } }else{ ?>
    <tr>
        <td colspan="9"  class="no_data_found"><?php echo $ld['no_data_found']?></td>
    </tr>
<?php }?>
</tbody>
</table>
<?php if(isset($contact_info) && sizeof($contact_info)){?>
    <div id="btnouterlist" class="btnouterlist">
        <?php if($svshow->operator_privilege("contacts_remove")){?>
        <div class="am-u-lg-4 am-u-md-4 am-u-sm-12  am-hide-sm-only"style="left:3px;">
            <label style="float:left;margin-right:10px; margin-top:5px;" class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
            <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" onclick="batch_action();" value="<?php echo $ld['batch_delete']?>" />
        </div>
        <?php }?>
        <div class="am-u-lg-8 am-u-md-7 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
<?php }?>
</div>
<?php echo $form->end();?>
<style>
    .ellipsis{
        width:150px;
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: capitalize;
        white-space: nowrap;
    }
</style>
<script>
    function batch_action(){
    	if(confirm(j_confirm_delete)){
            document.DeleteForm.submit();
        }
    }
</script>