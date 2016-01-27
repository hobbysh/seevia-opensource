<style>
table.quote_product > tbody > tr > th,table.quote_product > tbody > tr > td{border:none;}
.am-form-label{font-weight:bold;margin-top:5px;left:20px;}
</style>
<div class="listsearch">
    <?php echo $form->create('Quote',array('action'=>'/','id'=>'QuoteForm','name'=>"QuoteForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
    
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li>
            <label class="am-u-sm-4 am-u-lg-3 am-u-md-3 am-form-label"><?php echo $ld['quote_date'];?></label>
            <div class="am-u-sm-2 am-u-lg-3 am-u-md-3" style="padding:0 0.5rem;">
                <input style="min-height:38px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date1" value="<?php if(isset($date1)){echo $date1;} ?>" />
            </div>
            <em class="am-text-center am-u-sm-1 am-u-lg-1 am-u-md-1"  >-</em>
            <div class="am-u-sm-2 am-u-lg-3 am-u-md-3 am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:38px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php if(isset($date2)){echo $date2;} ?>" />
            </div>
        </li>
        		
        <li style="margin:7px 0px 10px 0px">
            <label class="am-u-lg-3 am-u-sm-4 am-u-md-3 am-form-label" ><?php echo $ld['email_status'];?></label>
            <div class=" am-u-lg-8 am-u-sm-6 am-u-md-8 am-u-end" >
                <select name="is_sendmail" id='is_sendmail'    data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value="-1" selected ><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if(isset($is_sendmail)&& $is_sendmail=='0'){?>selected<?php }?>><?php echo $ld['unsent'];?></option>
                    <option value="1" <?php if(isset($is_sendmail)&& $is_sendmail=='1'){?>selected<?php }?>><?php echo $ld['sent'];?></option>
                </select>
            </div>
        </li>
        			
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-sm-4  am-u-md-3 am-form-label" style="padding-top:12px;"><?php echo $ld['name_of_member'];?></label>
            <div class="am-u-lg-8 am-u-sm-6 am-u-md-8 ">
                <input style="height:38px;" type="text" name="customer_name" id="customer_name" value="<?php if(isset($customer_name)){echo $customer_name;} ?>"/> </div>
        </li>
        	
        <li style="margin:3px 0px 10px 0px">
            <label class="am-u-lg-3 am-u-sm-4 am-u-md-3 am-form-label"><?php echo $ld['product'].$ld['code'];?></label>
            <div class=" am-u-lg-8 am-u-sm-6  am-u-md-8 am-u-end">
                <input style="height:38px;" type="text" name="product_keywords" id="product_keywords" value="<?php if(isset($product_keywords)){echo $product_keywords;}?>"/>
            </div>
            	
        </li>
          <li style="margin:7px 0px 10px 0px">
		        	<div class="am-u-lg-4 am-u-sm-5  am-u-md-5 am-form-label"> 
		             <label style="margin-top:-5px;" class="am-checkbox am-success am-fr"><input type="checkbox" name="pro_show" onclick="pro_show_change()" value="1" <?php if(isset($showitem)&&$showitem==1){echo "checked=checked";} ?>/>
		                 <?php echo $ld['show'].$ld['details'];?></label>&nbsp;&nbsp;
		                </div>
		             <div class="am-u-lg-5 am-u-sm-5 am-u-md-5 "style="margin-left:20px;">
                  			<input  style="margin-top:5px;"  class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search']?>"  class="search_article">
                         </div>
            </li>
    </ul>
    				
    <?php echo $form->end();?>
</div>
<p class="am-u-md-12 am-text-right am-btn-group-xs"style="margin-right:10px">
				<a class="am-btn am-btn-warning am-radius am-btn-sm " href="/admin/quotes/view/0">
				<span class="am-icon-plus"></span>
				<?php echo $ld['add']?>
				</a>
</p>
<div class="am-u-md-12 am-u-sm-12">
    <?php echo $form->create('Quote',array('action'=>'/','name'=>'QuoteForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th style="width:20%;"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['name_of_member'];?></b></label></th>
            
            <th style="width:15%;"class="am-hide-sm-down"><?php echo $ld['inquire_date'];?></th>
            <th style="width:15%;"><?php echo $ld['status'];?></th>
            <th style="width:20px;"><?php echo $ld['quoted_by'];?></th>
            <th style="width:20px;"class="am-hide-sm-down"><?php echo $ld['quote_date'];?></th>
            <th style="width:30%;"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($quotes_list)&&sizeof($quotes_list)>0){foreach($quotes_list as $k => $v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Quote']['id']?>" /></span><?php if(isset($v['Quote']['customer_name'])){echo $v['Quote']['customer_name'];}?>
                    <?php if(isset($v['Quote']['contact_person'])&&!empty($v['Quote']['contact_person'])){echo '-'.$v['Quote']['contact_person'];}?></label></td>
                
                <td class="thwrap am-hide-sm-down"><?php if(isset($v['Quote']['inquire_date'])){echo date('Y-m-d',strtotime($v['Quote']['inquire_date']));}?></td>
                <td class="thwrap "><?php if(isset($v['Quote']['is_sendmail'])){if($v['Quote']['is_sendmail']=="1"){echo $ld['sent'];}else{echo $ld['unsent'];}}?></td>
                <td class="thwrap "><?php if(isset($v['Quote']['quoted_by'])){echo $v['Quote']['quoted_by'];}?></td>
                <td class="thwrap am-hide-sm-down"><?php if(isset($v['Quote']['created'])){echo date('Y-m-d',strtotime($v['Quote']['created']));}?></td>
                <td class="am-action">
                    <?php
                    if(isset($v['Quote']['is_sendmail'])&&$v['Quote']['is_sendmail']=='0'){?>
                        
                          <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/quotes/view/'.$v['Quote']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                         </a>
				        <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'/quotes/Remove/<?php echo $v['Quote']['id']; ?>'}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                   <?php  }else{
                        echo $html->link($ld['more'],"/quotes/view/{$v['Quote']['id']}",array("class"=>"am-btn am-btn-default  am-btn-xs  am-btns",'escape' => false));
                    }
                    ?>
                </td>
            </tr>
            <?php if(isset($v['QuoteProduct'])){ ?>
            <tr>
                <td colspan="6">
                    <table class="am-table am-table-compact quote_product">
                        <tbody>
                        <?php foreach($v['QuoteProduct'] as $vv){ ?>
                        <tr style="font-size:12px;">
                                <td width="5%">&nbsp;</td>
                                <td style="width:40%;"><?php if(isset($vv['QuoteProduct']['product_code'])){echo $vv['QuoteProduct']['product_code']; echo isset($quote_product_list[$vv['QuoteProduct']['product_code']])?'-'.$quote_product_list[$vv['QuoteProduct']['product_code']]:'';}?></td>
                                <td style="width:15%;"><?php if(isset($vv['QuoteProduct']['brand_code'])){echo $vv['QuoteProduct']['brand_code'];}?>&nbsp;</td>
                                <td style="width:5%;"><?php if(isset($vv['QuoteProduct']['qty_offered'])){echo $vv['QuoteProduct']['qty_offered'];}?>&nbsp;</td>
                                <td style="width:5%;"><?php if(isset($vv['QuoteProduct']['qty_requested'])){echo $vv['QuoteProduct']['qty_requested'];}?>&nbsp;</td>
                                <td style="width:5%;"><?php if(isset($vv['QuoteProduct']['offered_price'])){echo $vv['QuoteProduct']['offered_price'];}?>&nbsp;</td>
                                <td style="width:5%;"><?php if(isset($vv['QuoteProduct']['target_price'])){echo $vv['QuoteProduct']['target_price'];}?></td>
                                <td style="width:5%;"><?php if(isset($vv['QuoteProduct']['data_code'])){echo $vv['QuoteProduct']['data_code'];}?>&nbsp;</td>
                                <td style="width:15%;"><?php if(isset($vv['QuoteProduct']['payment_terms'])){echo $vv['QuoteProduct']['payment_terms'];}?>&nbsp;</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php } ?>
        <?php }}else{?>
            <tr>
                <td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($quotes_list) && sizeof($quotes_list)){?>
        <div id="btnouterlist" class="btnouterlist">
            <div class="am-u-lg-6 am-u-sm-12 am-u-md-12">
                <label style="margin:5px 5px 5px 0px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
                <span><select id="barch_opration_select" data-am-selected  onchange="quote_opration_select_onchange(this)">
                    <option value="0"><?php echo $ld['all_data']?></option>
                    <option value="export_csv"><?php echo $ld['batch_export']?></option>
                    <option value="batch_deletes"><?php echo$ld['batch_delete'] ?></option>
                </select></span>
                <span style="display:none;"><select id="export_csv" name="barch_opration_select_onchange" data-am-selected>
                    <option value="all_export_csv"><?php echo $ld['all_data']; ?></option>
                    <option value="choice_export"><?php echo $ld['choice_export']?></option>
                </select></span>
                <span><input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['submit']?>" onclick="quote_operation()" /></span>
               </div>
            <div class="am-u-lg-6 am-u-sm-12 am-u-md-12">
            <?php echo $this->element('pagers')?>
            <div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
    <?php echo $form->end();?>
</div>
<script type="text/javascript">
function quote_opration_select_onchange(obj){
    var barch_opration_select_onchange = document.getElementsByName("barch_opration_select_onchange[]");
    for( var i=0;i<barch_opration_select_onchange.length;i++ ){
        barch_opration_select_onchange[i].style.display = "none";
    }
    if(obj.value=="export_csv"){
        $("#export_csv").parent().show();
    }else{
        $("#export_csv").parent().hide();
    }
}

function quote_operation()
{ 
    var bratch_operat_check = document.getElementsByName("checkboxes[]");
    
    var barch_opration_select_type = document.getElementById("barch_opration_select").value;
    var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
	            if(bratch_operat_check[i].checked){
	                checkboxes.push(bratch_operat_check[i].value);
	            }
        }
         if(barch_opration_select_type=='batch_deletes'&&checkboxes==""){
            //alert(j_select_user);
            return;
          }//alert(barch_opration_select);
     if(barch_opration_select_type=='batch_deletes'){ 
     	 if(confirm("<?php echo $ld['confirm_delete']; ?>")){
     	 	 var sUrl = admin_webroot+"quotes/removeAll";//访问的URL地址
     	 	  $.ajax({
     	 	  type: "POST",
                url: sUrl,
                dataType: 'json',
                data:{checkboxes:checkboxes},
                success: function (result) {
                 window.location.href = window.location.href;
                }
     	 	 
     	 	 
     	 	 });
     	 }
      }
    ///////
}

function pro_show_change(){
    $("#QuoteForm").submit();
}
</script>