<style>
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align:text-top;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
.am-form-label{font-weight:bold;margin-top:5px;left:20px;}
.am-panel-title div{font-weight:bold;}
</style>
<div class="listsearch">
    <?php echo $form->create('',array('action'=>'/',"type"=>"get","class"=>"am-form am-form-horizontal",'name'=>"SearchForm"));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3 ">
        <li style="margin:0 0 10px 0">
            <label class=" am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"  ><?php echo $ld['added_time']?></label>
            <div class=" am-u-lg-3 am-u-md-3 am-u-sm-3" >
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date" value="<?php echo @$date;?>" />
            </div>
            <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center " style="margin-top:6px;" >-</em>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3  " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php echo @$date2;?>" />
            </div>
        </li>
         <li   style="margin:0 0 10px 0">
            <label class=" am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label   "><?php echo $ld['keyword']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-u-end " >
                <input placeholder="<?php echo $ld['name']?>/<?php echo $ld['name_of_member'];?>/<?php echo $ld['contacter'].'/'.$ld['email']?>" type="text" name="kword_name" style="height:37px;" value="<?php echo @$kword_name;?>"/>
            </div>
        </li>
        	
        <li  style="margin:0 0 10px 0;">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4  am-form-label  "><?php echo $ld['status']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="enquiry_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']?>'}" >
                    <option value=""><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if (isset($enquiry_status)&&$enquiry_status == 0){?>selected<?php }?>><?php echo $ld['unrecognized']?></option>
                    <option value="1" <?php if (isset($enquiry_status)&&$enquiry_status == 1){?>selected<?php }?>><?php echo $ld['confirmed']?></option>
                    <option value="2" <?php if (isset($enquiry_status)&&$enquiry_status == 2){?>selected<?php }?>><?php echo $ld['canceled']?></option>
                    <option value="3" <?php if (isset($enquiry_status)&&$enquiry_status == 3){?>selected<?php }?>><?php echo $ld['complete']?></option>
                </select>
             </div>
         </li>
          <li  style="margin:0 0 10px 0;">
         
            <div class="am-u-lg-9 am-u-md-9 am-u-sm-7  " style="padding-left:35px;">
            <input  type="submit" class="am-btn am-btn-success am-radius am-btn-sm search_article" value="<?php echo $ld['search'] ?>" />
             </div>
         </li>
   	
         				
    </ul>
    	
    <?php echo $form->end();?>
</div>
					<!---开始-->
<?php echo $form->create('',array("action"=>"/batch",'name'=>"DeleteForm","type"=>"get",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap "><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['name']?></b></label></th>
           
            <th class="thwrap am-hide-md-down"><?php echo $ld['attribute']?></th>
            <th><?php echo $ld['price']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['app_qty']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['name_of_member'];?></th>
            <th class="thwrap am-hide-sm-down"><?php echo $ld['contacter'].'/'.$ld['phone'].'/'.$ld['email']?></th>
            <th><?php echo $ld['status']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['added_time']?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($Enquiry_info) && sizeof($Enquiry_info)>0){
            foreach($Enquiry_info as $k=>$v){
                ?>
                <tr>
                    <td class="thwrap" style="maxwidth:125px;word-wrap:break-word; 
word-break:break-all; "><label  class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Enquiry']['id']?>" /></span>
                    <?php
                        $sku_code=$v['Enquiry']['part_num'];
                        $sku_code_arr=split(';',$v['Enquiry']['part_num']);
                        if(sizeof($sku_code_arr)>1){
                            foreach($sku_code_arr as $kk=>$vv){
                            echo isset($product_code_list[$vv])?$product_code_list[$vv]."<br>":' ';
                            }
                        }else{
                            echo isset($product_code_list[$sku_code])?$product_code_list[$sku_code]:' ';
                        }?>
                    </label>
                    	</td>
                    
                    <td class="thwrap am-hide-md-down"><?php $attribute_arr=split(';',$v['Enquiry']['attribute']);
                        if(sizeof($attribute_arr)>1){
                            foreach($attribute_arr as $ak=>$av){
                                echo isset($av)?$av."<br>":'&nbsp;&nbsp;';
                            }
                        }else{
                            echo $v["Enquiry"]["attribute"];
                        }?>
                    </td>
                    <td><?php $target_price_arr=split(';',$v['Enquiry']['target_price']);
                        if(sizeof($target_price_arr)>1){
                            foreach($target_price_arr as $tk=>$tv){
                                echo isset($tv)?$tv."<br>":'&nbsp;&nbsp;';
                            }
                        }else{
                            echo $v["Enquiry"]["target_price"];
                        }?></td>
                    <td class="thwrap am-hide-md-down"><?php $qty_arr=split(';',$v['Enquiry']['qty']);
                        if(sizeof($qty_arr)>1){
                            foreach($qty_arr as $qk=>$qv){
                                echo isset($qv)?$qv."<br>":'&nbsp;&nbsp;';
                            }
                        }else{
                            echo $v["Enquiry"]["qty"];
                        }?></td>
                    <td class="thwrap am-hide-md-down"><?php echo isset($user_info_list[$v["Enquiry"]["user_id"]])?$user_info_list[$v["Enquiry"]["user_id"]]:"";?></td>
                    <td class="thwrap am-hide-sm-down"><?php echo $v["Enquiry"]["contact_person"].'<br>'.$v["Enquiry"]["tel1"].'<br>'.$v["Enquiry"]["email"];?></td>
                    <td><?php
                        switch($v['Enquiry']['status']){
                            case 0:
                                echo $ld['unrecognized'];
                                break;
                            case 1:
                                echo $ld['confirmed'];
                                break;
                            case 2:
                                echo $ld['canceled'];
                                break;
                            case 3:
                                echo $ld['complete'];
                                break;
                        }
                        ?></td>
                    <td class="thwrap am-hide-md-down" style="width:75px;white-space:normal"><?php echo $v["Enquiry"]["created"]; ?></td>
                    <td><?php if($svshow->operator_privilege("contacts_detail")){?>
                  
                    		    <a class="am-btn am-btn-success am-btn-xs  am-seevia-btn am-seevia-btn-view" href="<?php echo $html->url( '/enquiries/'.$v['Enquiry']['id']); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['details_view']; ?>
                    </a>
                    			<?php }?></td>
                </tr>
            <?php } }else{ ?>
            <tr>
                <td colspan="10" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if($svshow->operator_privilege("contacts_remove")){?>
        <?php if(isset($Enquiry_info) && sizeof($Enquiry_info)){?>
        	           <div id="btnouterlist" class="btnouterlist"style="height:45px;margin-left;-3px;">
	                         <div class="am-u-lg-6 am-u-md-5 am-u-sm-12 am-hide-sm-down" style="margin-left:-3px;">	
					<label style="margin:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/> <?php echo $ld['select_all']?>  </label>&nbsp;&nbsp;
					<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="batch_action()"><?php echo $ld['batch_delete']?></button>
				     </div>
			 <?php }?>
				<div class="am-u-lg-6 am-u-md-7 am-u-sm-12">		
					<?php echo $this->element('pagers')?>
				</div>
				<div class="am-cf"></div>
		       </div>
        
        	  
    <?php }?>
</div>
<?php echo $form->end();?>
<script>
    function batch_action()
    {
    	 if(confirm("<?php echo $ld['confirm_delete'] ?>")){
        document.DeleteForm.submit();
         }
    }
</script>