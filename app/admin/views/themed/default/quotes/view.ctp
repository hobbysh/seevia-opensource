<style type="text/css">
    #priview_table{width:98%;margin:0 auto;}
    .input_price,.input_qty{width:50%;}
    .input_notes{width:100%;}
    #tbody1 .no_record td{padding:10px 0;}
    .email_info{margin-top:40px;}
    .email_info input[type=text]{min-width:200px;}
    .email_info select{min-width:205px;}
    .email_info textarea{min-width:198px;}
    .am-form-label{font-weight:bold;margin-top:5px;left:20px;}
</style>
<?php echo $form->create('QuoteProductForm',array('action'=>'/saveprouduct/','class'=>'am-form am-form-inline am-form-horizontal','name'=>"QuoteProductForm",'id'=>"QuoteProductForm","enctype"=>"multipart/form-data"));?>
<input id="type" name="type" value='<?php echo isset($_REQUEST["type"])?1:0?>' type="hidden"/>
<input id="enquiry_id" name="data1[Quote][enquiry_id]" value='<?php echo isset($enquiry_id)?$enquiry_id:0?>' type="hidden"/>
<div class="listsearch">
	
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-2" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
             <div  class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	            <label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label "><?php echo $ld['keyword'];?></label>
	            <div  class="am-u-lg-8 am-u-md-8 am-u-sm-8">
	                <input style="margin-right:10px;" type="text" id="product_keyword"/>
	            </div>
	        </div>
	        <div class="am-u-lg-3 am-u-md-1 am-u-sm-1 am-u-end">
	           <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['search'];?>" onclick="quoteSearchProduct()" />
	          </div>
        </li>
        	
        <li style="margin:0 0 10px 0">
        	  <div class="am-show-sm-only am-u-sm-2">&nbsp;</div>
              <div class="am-u-lg-6 am-u-md-5 am-u-sm-5" style="padding-left:8px;padding-right:29px;">
                <select name="select_goods" id="select_goods">
                    <option value=""><?php echo $ld['please_select'];?></option>
                </select>
            </div>
          <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 "style="top:3px;">
            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="+" onclick="submit_single()"/>
          </div>
        </li>
    
    </ul>
    
</div>
<div id="basic_information" class="am-panel-collapse am-collapse am-in">
    <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
        <table class="am-table" id="priview_table">
            <thead>
            <tr>
                <th><?php echo $ld['sku'];?></th>
                <th width="10%"><?php echo $ld['brand'];?></th>
                <th width="15%"><?php echo $ld['attribute'];?></th>
                <th width="8%"><?php echo $ld['qty_offered'];?></th>
                <th width="8%"><?php echo $ld['qty_req'];?></th>
                <th width="8%"><?php echo $ld['offered_price'];?></th>
                <th width="8%"><?php echo $ld['target_price'];?></th>
                <th width="20%"><?php echo $ld['notes'];?></th>
                <th  width="8%"><?php echo $ld['operate'];?></th>
            </tr>
            </thead>
            <tbody id="tbody1">
            <?php if(isset($quote_products_list)&&!empty($quote_products_list)){foreach($quote_products_list as $k=>$v){?>
                <tr>
                    <td><?php if(isset($v['QuoteProduct']['product_code'])){echo $v['QuoteProduct']['product_code'];}?>
                        <input type="hidden" name="data[<?php echo $k;?>][QuoteProduct][product_code]" value="<?php if(isset($v['QuoteProduct']['product_code'])){echo $v['QuoteProduct']['product_code'];}?>" />
                    </td>
                    <td><?php if(isset($v['QuoteProduct']['brand_code'])){echo $v['QuoteProduct']['brand_code'];}?>
                        <input type="hidden" name="data[<?php echo $k;?>][QuoteProduct][brand_code]" value="<?php if(isset($v['QuoteProduct']['brand_code'])){echo $v['QuoteProduct']['brand_code'];}?>" />
                    </td>
                    <td><?php if(isset($v['QuoteProduct']['data_code'])){echo $v['QuoteProduct']['data_code'];}?>
                        <input type="hidden" name="data[<?php echo $k;?>][QuoteProduct][data_code]" value="<?php if(isset($v['QuoteProduct']['data_code'])){echo $v['QuoteProduct']['data_code'];}?>" />
                    </td>
                    <td>
                        <input type="text" class="input_qty" name="data[<?php echo $k;?>][QuoteProduct][qty_offered]" value="<?php if(isset($v['QuoteProduct']['qty_offered'])){echo $v['QuoteProduct']['qty_offered'];}?>" />
                    </td>
                    <td>
                        <input type="text" class="input_qty" name="data[<?php echo $k;?>][QuoteProduct][qty_requested]" value="<?php if(isset($v['QuoteProduct']['qty_requested'])){echo $v['QuoteProduct']['qty_requested'];}?>" />
                    </td>
                    <td>
                        <input type="text" class="input_price" name="data[<?php echo $k;?>][QuoteProduct][offered_price]" value="<?php if(isset($v['QuoteProduct']['offered_price'])){echo $v['QuoteProduct']['offered_price'];}?>" />
                    </td>
                    <td>
                        <input type="text" class="input_price" name="data[<?php echo $k;?>][QuoteProduct][target_price]" value="<?php if(isset($v['QuoteProduct']['target_price'])){echo $v['QuoteProduct']['target_price'];}?>" />
                    </td>
                    <td><?php if(isset($v['QuoteProduct']['payment_terms'])){?>
                            <input type="text" class="input_notes" name="data[<?php echo $k;?>][QuoteProduct][payment_terms]" value="<?php if(isset($v['QuoteProduct']['payment_terms'])){echo $v['QuoteProduct']['payment_terms'];}?>" /><?php }?>
                    </td>
                    <td><a href="javascript:void(0)" onclick="delIndex(this)" style="margin-top:4px;" class="am-btn am-btn-default am-text-danger am-btn-xs am-radius"><?php echo $ld['delete'];?></a></td>
                </tr>
            <?php
            }
            }else{ ?>
                <tr class="no_record am-text-center"><td colspan="9"><?php echo $ld['no_record'] ?></td></tr>
            <?php }?>
            </tbody>
        </table>
        <ul class="email_info" style="list-style-type: none;">
            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['name_of_member'];?>:</strong></li>
            <li style="margin-bottom:10px;"><input type="text" style="width:200px;" id="customer_name" name="data1[Quote][customer_name]" value="<?php if(isset($quote_list['Quote']['customer_name'])){echo $quote_list['Quote']['customer_name'];}?>"/></li>
            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong>Email:</strong></li>
            <li style="margin-bottom:10px;"><input type="text" style="width:200px;" id="email" name="data1[Quote][email]" value="<?php if(isset($quote_list['Quote']['email'])){echo $quote_list['Quote']['email'];}?>"/></li>
            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong>Email <?php echo $ld['title'];?>:</strong></li>
            <li style="margin-bottom:10px;">
                <select style="width:200px;" name="data1[Quote][mail_title]">
                    <option value="0"><?php echo $ld['please_select'];?></option>
                </select>
            </li>
            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['contacter'];?>:</strong></li>
            <li style="margin-bottom:10px;"><input style="width:200px;" type="text" id="contact_person" name="data1[Quote][contact_person]" value="<?php if(isset($quote_list['Quote']['contact_person'])){echo $quote_list['Quote']['contact_person'];}?>"/></li>
            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['inquire_date'];?>:</strong></li>
            <li style="margin-bottom:10px;"><input style="width:200px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data1[Quote][inquire_date]" value="<?php if(isset($quote_list['Quote']['inquire_date'])){echo $quote_list['Quote']['inquire_date'];} ?>" /></li>
            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['remark'];?>:</strong></li>
            <li style="margin-bottom:10px;"><textarea style="width:200px;" id="remark" name="data1[Quote][remark]"><?php if(isset($quote_list['Quote']['remark'])){echo $quote_list['Quote']['remark'];}?></textarea></li>
        </ul>
        <div id="btnouterlist" class="btnouter">
            <div>
                <p>
                    <?php if(!isset($quote_list['Quote']['is_sendmail'])||$quote_list['Quote']['is_sendmail']=="0"){?>
                        <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="QuoteProduct_button" value="<?php echo $ld['save'];?>" onclick="Quote_submit(this,<?php if(isset($quote_list['Quote']['id'])){echo $quote_list['Quote']['id'];}else{echo '0';}?>)" />
                        <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="Email_button" value="<?php echo $ld['save_and_sendmail'];?>" onclick="Email_submit(this,<?php if(isset($quote_list['Quote']['id'])){echo $quote_list['Quote']['id'];}else{echo '0';}?>)" />
                    <?php }?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
</div>
<script>
    function Quote_submit(obj,id){
        var customer_name=document.getElementById('customer_name').value;
        var email=document.getElementById('email').value;
        var contact_person=document.getElementById('contact_person').value;
        if(customer_name==""){
            alert(j_customer_name_empty);
            return;
        }
        if(contact_person==""){
            alert(j_contact_person_empty);
            return;
        }
        if(confirm(j_sure_to_save)){
            var form = document.getElementById('QuoteProductForm');
            form.action = admin_webroot+'quotes/saveprouduct/'+id;
            form.submit();
        }
    }

    function Email_submit(obj,id){
        var customer_name=document.getElementById('customer_name').value;
        var email=document.getElementById('email').value;
        var contact_person=document.getElementById('contact_person').value;
        if(customer_name==""){
            alert(j_customer_name_empty);
            return;
        }
        if(email==""){
            alert(j_email_empty);
            return;
        }
        if(contact_person==""){
            alert(j_contact_person_empty);
            return;
        }
        if(confirm(j_save_send_email)){
            var form = document.getElementById('QuoteProductForm');
            form.action = admin_webroot+'quotes/sendemail/'+id;
            form.submit();
        }
    }

    //ajax关键字搜商品
    function quoteSearchProduct(){
        var product_keyword = Trim(document.getElementById("product_keyword").value);//搜索关键字
//        if(product_keyword.replace(/([\u0391-\uFFE5])/ig,'11').length<4){
//            alert(j_keyword_four);
//            return false;
//        }
        var sUrl = admin_webroot+"quotes/searchProducts/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {product_keyword: product_keyword},
            success: function (result) {
                if(result.flag=="1"){
                    var product_select_sel = document.getElementById('select_goods');
                    product_select_sel.innerHTML = "";
                    if(result.content){
                        for(i=0;i<result.content.length;i++){
                            var opt = document.createElement("OPTION");
                            opt.value = result.content[i]['Product'].code;
                            opt.text  = result.content[i]['Product'].code+"--"+result.content[i]['ProductI18n'].name;
                            product_select_sel.options.add(opt);
                        }
                    }
                    return;
                }
                if(result.flag=="2"){
                    var product_select_sel = document.getElementById('select_goods');
                    product_select_sel.innerHTML = "";
                    var opt = document.createElement("OPTION");
                    opt.value = "";
                    opt.text  = "<?php echo $ld['please_select']?>";
                    product_select_sel.options.add(opt);
                    alert(result.content);
                }
            }
        });
    }

    function submit_single(){
        var product = $('#select_goods').val();
        var tb = document.getElementById('priview_table');
        var index = tb.rows.length-1;
        var sUrl = admin_webroot+"quotes/submit_single/";//访问的URL地址

        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {product: product, k:index},
            success: function (result) {
                if(result.flag=="1"){
                    $("#tbody1 .no_record").hide();
                    $.each(result.content,function(k,v){
                        append_row(k,v);
                    });
                    var tbody = document.getElementById('tbody1');
                    return;
                }
                if(result.flag=="2"){
                    alert(result.content);
                }
            }
        });
    }

    function delIndex(obj) {
        var rowIndex = obj.parentNode.parentNode.rowIndex;//获得行下标
        var tb = document.getElementById("priview_table");
        tb.deleteRow(rowIndex);//删除当前行
    }

    //动态增加一行
    function append_row(k,v){
        var tbody = document.getElementById("tbody1");
        var newTR = tbody.insertRow(-1);
        var newTD0 = newTR.insertCell(-1);
        newTD0.innerHTML=v.code+'<input type="hidden" name="data['+k+'][QuoteProduct][product_code]" value="'+v.code+'" />';
        var newTD1 = newTR.insertCell(-1);
        newTD1.innerHTML=v.brand_id+'<input type="hidden" name="data['+k+'][QuoteProduct][brand_code]" value="'+v.brand_id+'" />';
        var newTD2 = newTR.insertCell(-1);
        newTD2.innerHTML=(v.attr!=null&&v.attr[0]!=null?v.attr[0]:'')+'<input type="hidden" name="data['+k+'][QuoteProduct][data_code]" value="'+(v.attr!=null&&v.attr[0]!=null?v.attr[0]:'')+'" />';
        var newTD3 = newTR.insertCell(-1);
        newTD3.innerHTML='<input type="text" class="input_qty" name="data['+k+'][QuoteProduct][qty_offered]" value="'+v.quantity+'" />';
        var newTD4 = newTR.insertCell(-1);
        newTD4.innerHTML='<input type="text" class="input_qty" name="data['+k+'][QuoteProduct][qty_requested]" value="" />';
        var newTD5 = newTR.insertCell(-1);
        newTD5.innerHTML='<input type="text" class="input_price" name="data['+k+'][QuoteProduct][offered_price]" value="'+v.shop_price+'" />';
        var newTD6 = newTR.insertCell(-1);
        newTD6.innerHTML='<input type="text" class="input_price" name="data['+k+'][QuoteProduct][target_price]" value="" />';
        var newTD7 = newTR.insertCell(-1);
        newTD7.innerHTML='<input type="text" class="input_notes" name="data['+k+'][QuoteProduct][payment_terms]" value="" />';
        var newTD8 = newTR.insertCell(-1);
        newTD8.innerHTML='<a href="javascript:void(0)" onclick="delIndex(this)" class="am-btn am-btn-danger am-btn-xs am-radius"><?php echo $ld['remove'] ?></a>';
    }
</script>