<style type="text/css">
#accordion .am-panel-bd .am-table > tbody > tr > th,#accordion .am-panel-bd .am-table > tbody > tr > td{border-top:none;}
#accordion .am-panel-bd .am-table > tbody > tr.border_show > th,#accordion .am-panel-bd .am-table > tbody > tr.border_show > td{border-top:1px solid #ddd;}

label{font-weight:normal;}
 
.am-form-horizontal .am-radio{padding-top:0px;;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.am-radio input[type="radio"]{margin-left:0px;}
.am-radio, .am-checkbox{ display: inline-block;}
div.attr_data{cursor: pointer;border: 1px solid #fff;margin:2px 0px;}
div.attr_data:hover{border: 1px solid #5eb95e;}
div.attr_data:hover span{cursor: pointer;}
.am-no{color: #dd514c;cursor: pointer;}
.img_select{max-width:150px;max-height:120px;text-align:center;border:1px solid #ddd;height:120px;margin-right:5px;margin-bottom:5px;}
.img_select.img_exist p{display:none;}
.related_dt{width:100%;max-height:300px;min-height:50px;overflow-y: auto;padding-left:10px;}
.related_dt dl{float:left;text-align:left;padding:3px 5px;;border:1px solid #ccc;margin:2px 5px;width:45%;display:block;white-space:nowrap;text-overflow: ellipsis;text-transform: capitalize;overflow:hidden;}
.related_dt dl:hover{cursor: pointer;border: 1px solid #5eb95e;color:#5eb95e;}
.related_dt dl:hover span{color:#5eb95e;}
.related_dt dl span{float:none;color: #ccc;padding:3px 2px 0px 2px;margin-right:5px;}
label{font-weight:normal;}
 
.am-form-horizontal .am-radio{padding-top:0px;;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.am-radio input[type="radio"]{margin-left:0px;}
.am-radio, .am-checkbox{ display: inline-block;}
div.attr_data{cursor: pointer;border: 1px solid #fff;margin:2px 0px;}
div.attr_data:hover{border: 1px solid #5eb95e;}
div.attr_data:hover span{cursor: pointer;}
.am-no{color: #dd514c;cursor: pointer;}
 
.material_info .am-form-label{margin-top:6px;}
.material_info .material_qty{margin-top:8px;}
.material_info .am-icon-minus{cursor: pointer;}

.relative_product_data:hover,.relative_article_data:hover{border:1px solid #5eb95e;}
.relative_product_data,.relative_article_data{ border: 1px solid #fff;cursor: pointer;}
.relative_product_data div:first-child,.relative_article_data div:first-child{text-align:left;white-space:nowrap;text-overflow: ellipsis;text-transform: capitalize;overflow:hidden;}
.relative_product_data div:last-child,.relative_article_data div:last-child{text-align:center;}

.taglist span.am-icon-minus{cursor: pointer;}
.am-selected-30sgr button{margin-top:10px;}



</style>
<script type="text/javascript">
    var marketPriceRate = 1.2;//市场价比例
    var integralPercent = 100;//积分比率
    var lang_desc = "";
    <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    lang_desc+='<p class="picture"><?php echo $html->image($v['Language']['img01'],array("class"=>"vmiddle"))?><input type="text" style="width:50px;" name="img_desc_batch[<?php echo $v['Language']['locale']?>][]"/></p>';
    <?php }}?>
</script>
<?php
echo $javascript->link('/skins/default/js/product');
?>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<script src="/plugins/javascriptviewer_jso.js" type="text/javascript"></script>
<?php echo $form->create('Product',array('action'=>'view/'.(isset($product_info["Product"]["id"])?$product_info["Product"]["id"]:""),'name'=>'theForm',"onsubmit"=>"return product_detail_checks();"));?>
<input type="hidden" id="productid" name="data[Product][id]" value="<?php echo isset($product_info['Product']['id'])?$product_info['Product']['id']:'0';?>">
<input type="hidden" name="data[Product][img_thumb]" value="<?php echo isset($product_info['Product']['img_thumb'])?$product_info['Product']['img_thumb']:'';?>">
<input type="hidden" name="data[Product][img_detail]" value="<?php echo isset($product_info['Product']['img_detail'])?$product_info['Product']['img_detail']:'';?>">
<input type="hidden" name="data[Product][img_original]" value="<?php echo isset($product_info['Product']['img_original'])?$product_info['Product']['img_original']:'';?>">
<input type="hidden" name="old_code" value="<?php echo isset($product_info['Product']['code'])?$product_info['Product']['code']:'';?>">
<input type="hidden" name="old_quantity" value="<?php echo isset($product_info['Product']['quantity'])?$product_info['Product']['quantity']:'0';?>" />
<input type="hidden" name="data[Product][option_type_id]" value="<?php echo isset($option_type_id)?$option_type_id:'0'; ?>" />
<!--页面传语言-->
<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    <input type="hidden" name="data[ProductI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>" />
    <input type="hidden" name="data[TagI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>" />
    <input type="hidden" name="data[ProductDownload][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>" />
<?php }}?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <li><a href="#pro_album"><?php echo $ld['product_album']?></a></li>
        <li><a href="#product_material"><?php echo $ld['material']; ?></a></li>
        <li><a href="#set_up"><?php echo $ld['advanced_config'];?></a></li>
        <?php if($option_type_id==0){ ?>
            <li><a href="#pro_att"><?php echo $ld['product_attribute']?></a></li>
        <?php }?>
        <?php if($is_sku==false&&$option_type_id==2){?>
            <li><a href="#sales_att"><?php echo $ld['sales_attribute'];?></a></li>
        <?php }?>
        <?php if($option_type_id==1){ ?>
            <li><a href="#pack_pro"><?php echo $ld['package_product']?></a></li>
        <?php }?>
        <li><a href="#related_product_article"><?php echo $ld['related_product_article']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view am-fr"  id="accordion" >
    <!-- 编辑按钮区域 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" />  <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
   <!-- 编辑按钮区域 -->
<div id="basic_info" class="am-panel am-panel-default">
<div class="am-panel-hd">
    <h4 class="am-panel-title"><?php echo $ld['basic_information']; ?><span style="margin-left:10px;">[<?php echo isset($pro_option_type_name[$option_type_id])?$pro_option_type_name[$option_type_id]:''; ?>]</span>
    </h4>
</div>
<div id="basic_information" class="am-panel-collapse am-collapse am-in">
<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
<table class="am-table">
<!--商品名称--->
<tr> 
    <th class="am-input-title" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['name']?></th>
</tr>
<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
<tr class="am-locale-<?php echo $k; ?>">
    <td><input type="text" style="width:200px;float:left;" id="product_name_<?php echo $v['Language']['locale'];?>"  maxlength="60" name="data[ProductI18n][<?php echo $k;?>][name]" value="<?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['name']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em>*</em></td>
</tr>
<?php }} ?>
<!--手机描述--->
<tr>
    <th  style="padding-top:24px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['simple_description']; ?></th>
</tr>
<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
<tr>
    <td><textarea style="width:400px;float:left;" name="data[ProductI18n][<?php echo $k;?>][description02]"><?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['description02']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="margin-top:8px;"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
</tr>
<?php }}?>
<!-- 商品描述 -->
<tr>
    <th class="am-input-title"  rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['description']?></th>
</tr>
<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){ ?>
<?php if($show_edit_type){?>
    <tr><!--------编辑器----------->
        <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
            <textarea id="product_description_id<?php echo $v['Language']['locale'];?>" name="data[ProductI18n][<?php echo $k;?>][description]" style="width:auto;height:300px;"><?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['description']:'';?></textarea>
            <script>
                var editor;
                KindEditor.ready(function(K) {
                    editor = K.create('#product_description_id<?php echo $v['Language']['locale'];?>', {
                            width:'85%',
                            items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
                                'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
                                'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
                                'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
                                'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
                            langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false
                        }
                    );
                });
            </script>
        </td>
    </tr>
<?php }else{?>
    <tr>
        <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
            <textarea cols="80" id="product_description_id<?php echo $v['Language']['locale'];?>" name="data[ProductI18n][<?php echo $k;?>][description]" rows="10"><?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['description']:'';?></textarea>
            <?php echo $ckeditor->load("product_description_id".$v['Language']['locale']); ?></td>
    </tr>
<?php }?>
<?php }}?>


<!--商品货号-->
     <tr >
    <th  style="margin-top:-3px;padding-top:16px;"><?php echo $ld['product_code']?></th>
    <td>
        <input type="text" style="width:200px;margin-bottom:5px;" id="product_num" onchange="checkProductCode()"  name="data[Product][code]" value="<?php echo isset($product_info['Product']['code'])?$product_info['Product']['code']:'';?>" />
        <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php if($svshow->operator_privilege("product_code_creates_view")){ echo $ld["prod_auto_code"];}?>" onclick="auto_code()" />
        <?php if(isset($id)&&$id==0){?>
            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="huohao()" value="<?php echo $ld['product_code_creates']?>" />
        <?php }?>
        <input type="hidden" id="product_num_h" value="0">
        <?php if(isset($configs['product_code_explain'])&&$configs['product_code_explain']!=""){?>
            <br /><span class="helpword" >
                        <?php echo $html->link($ld['rule_description'],"/applications/view/206",array('target'=>"_blank")); ?></span>
        <?php }?>
    </td>
</tr>
<!--商品品牌-->
<tr>
    <th style="padding-top:16px;"><?php echo $ld['product_brand']?></th>
    <td><input type="text" style="width:200px;margin-right:5px;float:left;" id="brand_keyword" style="width:30%;"/><input class="am-btn am-btn-success am-btn-sm" type="button" value="<?php echo $ld['search']?>"  onclick="search_brand()"/>
        <div id='select_brand' style="margin-top:20px;">
                <input type="hidden" value="" id="product_brand_id_h"/>
                <select name="data[Product][brand_id]" id="product_brand_id" data-am-selected onchange="changeProductCode(this)">
                <option value="0"><?php echo $ld['select_brands']?></option>
                <?php if(isset($brand_tree) && sizeof($brand_tree)>0){?>
                    <?php foreach($brand_tree as $k=>$v){?>
                        <option value="<?php echo $v['Brand']['id']?>" <?php if($product_brand_id == $v['Brand']['id']){?>selected<?php }?>><?php echo $v['BrandI18n']['name']?></option>
                    <?php }}?>
                </select>
            <?php if($svshow->operator_privilege("products_add_brand")){?>
                <input class="am-btn am-btn-success am-radius am-btn-sm"   type="button" data-am-modal="{target: '#brand', closeViaDimmer: 0, width: 400, height: 275}" value="<?php echo $ld['quick_add_brand']?>" />
            <?php }?>
        </div></td>
</tr>
<!--商品分类-->
<tr>
    <th style="padding-top:13px;"><?php echo $ld['product_categories']; ?></th>
    <td><div>
            <select data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>'}" name="data[Product][category_id]" id="product_category_id">
                <option value="0"><?php echo $ld['select_categories'];?></option>
                <?php if(isset($category_tree) && sizeof($category_tree)>0){ foreach($category_tree as $first_k=>$first_v){?>
                    <option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($product_category_id == $first_v['CategoryProduct']['id'] || (isset($fromcategory_id) && $fromcategory_id == $first_v['CategoryProduct']['id'])){?>selected<?php }?> ><?php echo $first_v['CategoryProductI18n']['name'];?></option>
                    <?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
                        <?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
                            <option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($product_category_id == $second_v['CategoryProduct']['id'] || (isset($fromcategory_id) && $fromcategory_id == $second_v['CategoryProduct']['id'])){?>selected<?php }?> >|--<?php echo $second_v['CategoryProductI18n']['name'];?></option>
                            <?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
                                <?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
                                    <option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($product_category_id == $third_v['CategoryProduct']['id'] || (isset($fromcategory_id) && $fromcategory_id == $third_v['CategoryProduct']['id'])){?>selected<?php }?> >|----<?php echo $third_v['CategoryProductI18n']['name'];?></option>
                                <?php }}}}}}?>
            </select><?php
            if($svshow->operator_privilege("products_add_category")){
                ?><input class="am-btn am-btn-success am-radius am-btn-sm" style="margin-left:4px;" type="button" data-am-modal="{target: '#productcat', closeViaDimmer: 0, width: 400, height: 275}" value="<?php echo $ld['quick_add_category']?>" /><em>*</em><?php
            }?>
        </div></td>
</tr>
<!--扩展分类-->
<tr>
    <th style="padding-top:15px;"><?php echo $ld['extended_cotegory']?></th>
    <td><input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="addOtherCat()" value="<?php echo $ld['add']?>" /><p><span id="othercat">
                            <?php if(!empty($other_category_list)){foreach($other_category_list as $k=>$v){?>
                                <select name="other_cat[]">
                                    <option value="0"><?php echo $ld['please_select']?></option>
                                    <?php if(isset($category_tree) && sizeof($category_tree)>0){?>
                                        <?php foreach($category_tree as $first_k=>$first_v){?>
                                            <option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($v['ProductsCategory']['category_id'] == $first_v['CategoryProduct']['id']){?>selected<?php }?>><?php echo $first_v['CategoryProductI18n']['name'];?></option>
                                            <?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
                                                <?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
                                                    <option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($v['ProductsCategory']['category_id'] == $second_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
                                                    <?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
                                                        <?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
                                                            <option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($v['ProductsCategory']['category_id'] == $third_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
                                                        <?php }}}}}}?>
                                </select>
                            <?php }}?>
                            </span></p></td>
</tr>
<!--商品类目-->
<tr>
    <th style="padding-top:12px;"><?php echo $ld["prod_category_commodity"];?></th>
    <td>
        <div>
            <select data-am-selected name="data[Product][category_type_id]" id="product_category_type_id">
                <option value="0"><?php echo $ld['select_categories']?></option>
                <?php if(isset($category_type_tree) && sizeof($category_type_tree)>0){foreach($category_type_tree as $first_k=>$first_v){?>
                    <option value="<?php echo $first_v['CategoryType']['id'];?>" <?php if($product_category_type_id == $first_v['CategoryType']['id'] || (isset($fromcategory_type_id) && $fromcategory_type_id == $first_v['CategoryType']['id'])){?>selected<?php }?> ><?php echo $first_v['CategoryTypeI18n']['name'];?></option>
                    <?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
                        <?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
                            <option value="<?php echo $second_v['CategoryType']['id'];?>" <?php if($product_category_type_id == $second_v['CategoryType']['id'] || (isset($fromcategory_type_id) && $fromcategory_type_id == $second_v['CategoryType']['id'])){?>selected<?php }?> >&nbsp;&nbsp;<?php echo $second_v['CategoryTypeI18n']['name'];?></option>
                            <?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
                                <?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
                                    <option value="<?php echo $third_v['CategoryType']['id'];?>" <?php if($product_category_type_id == $third_v['CategoryType']['id'] || (isset($fromcategory_type_id) && $fromcategory_type_id == $third_v['CategoryType']['id'])){?>selected<?php }?> >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryTypeI18n']['name'];?></option>
                                <?php }}}}}}?>
            </select>
            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" data-am-modal="{target: '#productcattype', closeViaDimmer: 0, width: 400, height: 275}" value="<?php echo $ld['quick_add_category_type']?>" />
        </div>
    </td>
</tr>
<?php if(isset($yb_cid)&&!empty($yb_cid)){?>
    <tr>
        <th><?php echo $ld["prod_category_commodity"];?></th>
        <td>
            <select id="yb_cid" onchange="changeProductCode(this)" size="5" style="height:auto;width:200px;"><option value=""><?php echo $ld['please_select']?></option>
                <?php foreach ($yb_cid as $k => $v) { ?><option value="<?php echo $k;?>" <?php if(isset($product_cid)&&$k==$product_cid)echo 'selected'?>><?php echo $v;?></option><?php }?>
            </select>
        </td>
    </tr>
<?php }?>
<!--进货价-->
<?php if (isset($SVConfigs["show_purchase_price"])&&$SVConfigs["show_purchase_price"]==1){ ?>
    <tr><th><?php echo $ld['purchase_price']?></th><td><input type="text" style="width:200px;" name="data[Product][purchase_price]" value="<?php echo isset($product_info['Product']['purchase_price'])?$product_info['Product']['purchase_price']:"123123123";?>" /></td></tr>
<?php }?>
<!--本店售价-->
<tr class="border_show">
    <th style="padding-top:15px;"><?php echo $ld['shop_price']?></th>
    <td><input type="text" id="shop_price" style="width:200px;margin-right:5px;float:left;" name="data[Product][shop_price]" value="<?php echo isset($product_info['Product']['shop_price'])?$product_info['Product']['shop_price']:'';?>" onblur="priceSetted()" /><input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="marketPriceSetted()" value="<?php echo $ld['by_market_price']?>" style="*float:left;" /><em>*</em></td>
</tr>
<!--市场售价-->
<tr>
    <th style="padding-top:15px;"><?php echo $ld['market_price']?></th>
    <td><input type="text" id="market_price" style="width:200px;margin-right:5px;float:left;" name="data[Product][market_price]" value="<?php echo isset($product_info['Product']['market_price'])?$product_info['Product']['market_price']:'';?>" /><input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="integral_market_price()" value="<?php echo $ld['rounded']?>" /></td>
</tr>
<!--自定义价格-->
<tr>
    <th style="padding-top:15px;"><?php echo $ld['custom_price'];?></th>
    <td><input type="text" style="width:200px;" id="market_price" name="data[Product][custom_price]" value="<?php echo isset($product_info['Product']['custom_price'])?$product_info['Product']['custom_price']:'';?>" /></td>
</tr>
<!--促销价-->
<tr class="border_show">
    <th style="padding-top:15px;"><?php echo $ld['promotion_price']?></th>
    <td><div class="am-fl">
            <label class="am-checkbox am-success"><input type="checkbox" name="data[Product][promotion_status]" id="promotion_status" value="1" onclick="handlePromote(this.checked)" data-am-ucheck <?php if(isset($product_info['Product']['promotion_status'])&&$product_info['Product']['promotion_status']==1){echo "checked";}?> /></label>
        </div>
        <div class="am-fl">
            <input style="width:200px;" type="text" name="data[Product][promotion_price]" id="promote_price" value="<?php echo isset($product_info['Product']['promotion_price'])?$product_info['Product']['promotion_price']:'';?>" onblur="chaxiao()" /></div></td>
</tr>
<!--促销日期-->
<tr>
    <th style="padding-top:15px;"><?php echo $ld['promotion_date']?></th>
    <td><input style="min-height:35px;width:150px;float:left;margin-right:5px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date" value="<?php echo $start_date;?>" /><em class="am-fl" style="margin-right:5px;float:left;color:black;padding:0.35em 0px;margin-left:2px;top:2px">-</em><input style="min-height:35px;width:150px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="end_date" value="<?php echo $end_date;?>" /></td>
</tr>
<!-- 进货价 -->
<?php if(isset($configs['show_purchase_price'])&&$configs['show_purchase_price']){ ?>
    <tr>
        <th ><?php echo $ld['purchase_price'];?></th>
        <td><input style="width:200px;" type="text" name="data[Product][purchase_price]" value="<?php echo isset($product_info['Product']['purchase_price'])?$product_info['Product']['purchase_price']:'';?>" /></td>
    </tr>
<?php } ?>
<!--会员价格-->
<?php if(isset($user_rank_list)&&sizeof($user_rank_list)>0){?>
    <tr>
        <th ><?php echo $ld['member_price']?></th>
        <td><table>
                <?php foreach($user_rank_list as $k=>$v){?>
                    <tr>
                        <td><?php echo $v["UserRank"]["name"];?></td>
                        <td><input style="width:200px;" type="text" id="rank_product_price<?php echo $k?>" name="product_rank_price[<?php echo $v['UserRank']['id']?>]" value="<?php echo empty($product_rank[$v['UserRank']['id']]['ProductRank']['product_price'])?0:$product_rank[$v['UserRank']['id']]['ProductRank']['product_price'];?>"></td>
                        <td><?php echo $ld['discount_rate']?></td>
                        <td><input style="width:200px;" type="text" id="user_price_discount<?php echo $k?>" name="user_rank[<?php echo $v['UserRank']['id']?>]" value="<?php echo empty($v['UserRank']['discount'])?0:$v['UserRank']['discount'];?>"></td>
                        <td><label><input type="checkbox" value="1" name="product_rank_is_default_rank[<?php echo $v['UserRank']['id']?>]" <?php if(!empty($product_rank[$v['UserRank']['id']]['ProductRank']['is_default_rank'])&&$product_rank[$v['UserRank']['id']]['ProductRank']['is_default_rank']!=0){ echo "checked";}?> onclick="user_prince_check(this.checked,<?php echo $k?>)"><?php echo $ld['automatically_calculate']?></label></td>
                    </tr>
                <?php }?>
            </table></td>
    </tr>
<?php }?>
<?php if(isset($configs['show_purchase_price'])&&$configs['show_purchase_price']=='1'){ ?>
    <!--商品进货价-->
    <tr>
        <th ><?php echo $ld['purchase_price']?></th>
        <td><input style="width:200px;" type="text" name="data[Product][purchase_price]" value="<?php echo isset($product_info['Product']['purchase_price'])?$product_info['Product']['purchase_price']:'0';?>" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /></td>
    </tr>
<?php } ?>
<tr><th style="padding-top:37px;"> <?php echo $ld['quantity_discount']?></th>
    <td  style="padding:0">
        <div>
            <table id="addr-tables" class="listtable" style="border-collapse:separate; border-spacing:5px;">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th style="padding-left:60px;"><?php echo $ld['app_qty']?></th>
                    <th style="padding-left:60px;"><?php echo $ld['price']?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($pv_infos)&&!empty($pv_infos)){foreach($pv_infos as $v){?>
                    <tr style="margin-bottom:5px;">
                        <td>
                            <a href="javascript:;" onclick="removeaddr(this)">[-]</a></td>
                        <td><input style="width:80%" type="text" name="volume_number[]" value="<?php echo $v['ProductVolume']['volume_number'];?>"/></td>
                        <td><input style="width:80%" type="text" name="volume_price[]" value="<?php echo $v['ProductVolume']['volume_price'];?>" /></td>
                    </tr>
                <?php }}?>
                <tr>
                    <td>
                        <a href="javascript:;" onclick="addaddr(this)">[+]</a></td>
                    <td><input style="width:80%" type="text" name="volume_number[]" value=""/></td>
                    <td><input style="width:80%" type="text" name="volume_price[]" id="add_id" value="" name="add_id" /></td>
                </tr>
                </tbody>
            </table>
        </div>
    </td>
</tr>
<!--商品重量-->
<tr>
    <th style="padding-top:15px"><?php echo $ld['weight']?></th>
    <td><input type="text" style="width: 4em;float:left;margin-right:5px;" name="data[Product][weight]" value="<?php echo isset($product_info['Product']['weight'])?$product_info['Product']['weight']:'';?>" />
        <select name="weight_unit" data-am-selected>
            <option value="1" <?php if(isset($weight_unit)&&$weight_unit=="1"){echo "selected";}?> ><?php echo $ld['kilogram']?></option>
            <option value="0.001" <?php if(isset($weight_unit)&&$weight_unit=="0.001"){echo "selected";}?>><?php echo $ld['gram']?></option>
        </select></td>
</tr>
<!--上架-->
<tr>
    <th ><?php echo $ld['for_sale']?></th>
    <td><label style="padding:0 2rem;" class="am-checkbox am-success"><input type="checkbox" name="data[Product][forsale]" value="1" data-am-ucheck <?php if((isset($product_info['Product']['forsale'])&&$product_info['Product']['forsale']==1)||!isset($product_info['Product']['forsale'])){echo "checked";}?> /><?php echo $ld['toggle_for_sale']?></label></td>
</tr>
<!--商品库存数量-->
<tr>
    <th style="padding-top:15px"><?php echo $ld['quantity']?></th>
    <td><input style="width:200px;float:left;" type="text" id="num" class="num" name="data[Product][quantity]" value="<?php echo isset($product_info['Product']['quantity'])?$product_info['Product']['quantity']:'999';?>" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /><em>*</em></td>
</tr>
<!--商品库存数量-->
<tr>
    <th style="padding-top:15px"><?php echo $ld['unit']?></th>
    <td><input style="width:200px;float:left;" type="text" name="data[Product][unit]" value="<?php echo isset($product_info['Product']['unit'])?$product_info['Product']['unit']:'';?>" /></td>
</tr>
<!--商品创建时间-->
<tr class="border_show">
    <th><?php echo $ld['create_time']?></th>
    <th align="left"><?php echo isset($product_info['Product']['created'])?$product_info['Product']['created']:'';?></th>
</tr>
<!--商品修改时间-->
<tr>
    <th><?php echo $ld['last_modified']?></th>
    <th align="left"><?php echo isset($product_info['Product']['modified'])?$product_info['Product']['modified']:'';?></th>
</tr>
</table>
</div>
</div>
</div>
<div id="pro_album" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title"><?php echo $ld['product_album']?></h4>
    </div>
    <div id="product_album" class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal " style="padding-bottom:0;">
            <?php
            	$ProductGalleryMaxLength=5;
            	$ProductGalleryLength=isset($product_gallery)?sizeof($product_gallery):5;//当前商品相册数
            ?>
            <div id="ph_show" class="show_border am-class ">
            		<ul class="am-avg-lg-6 am-avg-md-3 am-avg-sm-2 gallerytables" id="gallery-tables">
				<li style="font-weight:bold;">
					<div style="height:120px;"><?php echo $ld['picture_preview'] ?></div>
					<div>&nbsp;</div>
					<?php foreach($backend_locales as $k => $v){?>
					<div style="padding:18px 0px;"><?php echo $k==0?$ld['image_description']:'&nbsp;';?></div>
					<?php } ?>
					<?php if(isset($product_gallery)){ ?>
					<div style="padding-top:5px;"><?php echo $ld['sort']?></div>
					<?php } ?>
				</li>
            		<?php
            			for($i=0;$i<ceil($ProductGalleryMaxLength/5);$i++){ 
            				for($j=$i*5;$j<$i*5+5;$j++){
            					if($j%5==0&&$j!=0){continue;}
            					if($j>$ProductGalleryMaxLength-1){break;}$k=$j+1;
            		?>
            			<li>
            				<!-- 添加图片 -->
                                   <div class="img_select" onclick="select_img('product_add_img_0<?php echo $j+1;?>')"><?php if(!isset($product_gallery[$j]['ProductGallery']['img_thumb'])){?><p style="padding-top:50px;" id="product_add_img_0<?php echo $j+1;?>_pic"><?php echo "+".$ld['add_picture'] ?></p><?php }?>
	                                            <?php if(isset($product_gallery[$j]['ProductGallery']['img_thumb'])) $img_thumb_format = explode("http://",$product_gallery[$j]['ProductGallery']['img_thumb']); ?>
	                                            <?php if(isset($product_gallery[$j]['ProductGallery']['img_thumb'])&&isset($img_thumb_format)&&count($img_thumb_format)==1){
	                                                echo $html->image((isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!="")?IMG_HOST.$product_gallery[$j]['ProductGallery']['img_thumb']:"/media/default_no_photo.png",array('id'=>"show_product_add_img_0$k","onclick"=>"select_img('product_add_img_0$k')"));
	                                            }else{
	                                                echo $html->image((isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!="")?$product_gallery[$j]['ProductGallery']['img_thumb']:"/media/default_no_photo.png",array('id'=>"show_product_add_img_0$k","onclick"=>"select_img('product_add_img_0$k')"));
	                                            }?>
	                                    </div>
						<div style="padding-right:20px;max-width:170px;">
							<div  id='td_<?php echo $j+1;?>' class="am-text-center" style="white-space:nowrap">
							<?php
								if(isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!=""){
									if(isset($product_info['Product']['img_thumb'])&&$product_gallery[$j]['ProductGallery']['img_thumb']==$product_info['Product']['img_thumb']){
									echo $ld['default_picture'];
								}else{
									echo $html->link($ld['set_as_default_picture'],"/products/set_default_picture/".$product_gallery[$j]['ProductGallery']['id']."/".(isset($product_info['Product']['id'])?$product_info['Product']['id']:''),'',false,false);
								}
							?>
								<a href="javascript:void(0);" onclick="delProImg('<?php echo $product_gallery[$j]['ProductGallery']['id'];?>','<?php echo $j+1;?>','<?php echo isset($product_info['Product']['id'])?$product_info['Product']['id']:'';?>')"><?php echo $ld["delete"];?></a><?php } ?>&nbsp;
							</div>
						</div> 
            				 <?php foreach($backend_locales as $k => $v){?>
            				 		<div class="am-padding-left-0" style="padding-right:5px;margin-right:5px;">
            				 				 <input style="float:left;margin-top:10px;margin-bottom:10px;max-width:130px;" type="text" id ="ProductGalleryI18n_<?php echo $j+1;?>_<?php echo $k;?>_description" name="data[product_gallery_data][<?php echo $j;?>][ProductGalleryI18n][<?php echo $k;?>][description]" value="<?php echo !empty($product_gallery[$j]['ProductGalleryI18n'][$v['Language']['locale']]['description'])?$product_gallery[$j]['ProductGalleryI18n'][$v['Language']['locale']]['description']:'';?>"/><?php if(sizeof($backend_locales)>1){?><span style="margin-top:20px;" class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?>
                                        				<input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGalleryI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>"/>
                                        				<div class="am-cf"></div>
            				 		</div>
            				 <?php } ?>
            				 <input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_thumb]" id="product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!="")?$product_gallery[$j]['ProductGallery']['img_thumb']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_detail]" id="img_detail_product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_detail']!="")?$product_gallery[$j]['ProductGallery']['img_detail']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_big]" id="img_big_product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_big']!="")?$product_gallery[$j]['ProductGallery']['img_big']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_original]" id="img_original_product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_original']!="")?$product_gallery[$j]['ProductGallery']['img_original']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][id]" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['id']!="")?$product_gallery[$j]['ProductGallery']['id']:'';?>">
					<?php if(isset($product_gallery)&&$j<sizeof($product_gallery)-1){ ?>
						<div style="padding-top:5px;">
							<input type="button" value=">>" style="margin-bottom:5px;" class="am-btn am-btn-success am-btn-xs" onClick="changeOrder(<?php echo $product_gallery[$j]['ProductGallery']['product_id']; ?>,<?php echo $product_gallery[$j]['ProductGallery']['id']; ?>)" />
						</div>
					<?php } ?>
            			</li>
            			
            		<?php }} ?>
            		</ul>
            </div>
            <?php if(!empty($product_info['Product']['code'])){ ?>
            <div class="am-g" style="margin:16px auto;border-bottom: 1px solid #eee;">
                <h4 class="am-panel-title"><?php echo $ld['rotate_picture']; ?></h4>
            </div>
            <div style="margin-top:1.5em;">
                <table>
                    <tr>
                        <th style="width:120px;margin-right:1em;">&nbsp;</th>
                        <td>
                            <div id="image_holder_x" class="imageholder">
                                <?php $pro_360_defaultImg=file_exists(WWW_ROOT.'/media/360Rotation/'.$product_info['Product']['code'].'/'.$product_info['Product']['code'].".jpg")?'/media/360Rotation/'.$product_info['Product']['code'].'/'.$product_info['Product']['code'].".jpg":$configs['shop_default_img']; ?>
                                <img id="product_image_x" src="<?php echo $server_host.$pro_360_defaultImg; ?>" style="width:150px;height:150px;" >
                            </div>
                            <div class="action-span" style="margin-bottom:1.5rem;">
                                <?php if(file_exists(WWW_ROOT.'/media/360Rotation/'.$product_info['Product']['code'].'/'.$product_info['Product']['code'].".jpg")){ ?>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-text-center"><a id="start_presentation" href="javascript:void(0);"><?php echo $ld['preview']?></a></div>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-text-center"><a class="addbutton" href="javascript:void(0);" onclick="delete_img('<?php echo $product_info['Product']['code'];?>')"><?php echo $ld['delete']?></a></div>
                                    <div class="am-cf"></div>
                                <?php }?>
                            </div>
                        </td>
                        <td><p class="action-span" style="text-align:left;padding-left:0.5em;"><a class="addbutton" href="javascript:;" onclick="upload_img('<?php echo $product_info['Product']['code'];?>')"><?php echo $ld['upload_wheel']?></a><span>(请上传后缀名为.jpg的72张图片)</span></p></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<div id="product_material" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title"><?php echo $ld['material'];?></h4>
    </div>
    <div class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
            <div class="am-g am-text-right">
                <a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="javascript:void(0);" onclick="addMaterial()">
                    <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
                </a>
            </div>
            <div id="material" class="am-g">
                <select id="material_code" style="display:none;">
                    <option value="0"><?php echo $ld['please_select']?></option>
                    <?php if(isset($material_tree) && sizeof($material_tree)>0){ foreach($material_tree as $mk=>$mv){?>
                        <option value="<?php echo $mv['Material']['code']?>"><?php echo $mv['MaterialI18n']['name'];?>(<?php echo $mv['Material']['unit'];?>)</option>
                    <?php }}?>
                </select>
                <div class="material_list" id="material_list">
                    <?php if(!empty($product_material_list)){foreach($product_material_list as $k=>$v){?>
                        <div class="material_info">
                            <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label" style="padding-top:12px;"><span class="am-icon-minus"  onclick='removeMaterial(this)'></span></label>
                            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><select name="material_code[]" class="material_code">
                                <option value="0"><?php echo $ld['please_select']?></option>
                                <?php if(isset($material_tree) && sizeof($material_tree)>0){ foreach($material_tree as $mk=>$mv){?>
                                    <option value="<?php echo $mv['Material']['code']?>" <?php if($v['ProductMaterial']['product_material_code'] == $mv['Material']['code'] ){echo 'selected';}?>><?php echo $mv['MaterialI18n']['name'];?>(<?php echo $mv['Material']['unit'];?>)</option>
                                <?php }}?>
                            </select></div>
                            <label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label unit" style="padding-top:13px;"><?php echo $ld['usage_amount']; ?></label>
                            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><input type="text" class="material_qty" name="material_qty[]" value="<?php echo $v['ProductMaterial']['quantity'];?>"></div>
                            <div class="am-cf"></div>
                        </div>
                    <?php }}?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="set_up" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title"><?php echo $ld['advanced_config']; ?></h4>
    </div>
    <div id="advanced_set_up" class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
            <table class="am-table" id="product_url">
                <!--映射路径--->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['routeurl']?></th>
                    <td><input style="width:200px;" id="Route_url" onchange="checkrouteurl()" type="text" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" /><input type="hidden" id="route_url_h" value="0">(<?php echo $ld['routeurl_desc'] ?>)</td>
                </tr>
                <!--赠送积分数-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['given_points']?></th>
                    <td><input style="width:200px;" type="text" name="data[Product][point]" value="<?php echo isset($product_info['Product']['point'])?$product_info['Product']['point']:'';?>" /></td>
                </tr>
                <?php if(isset($configs['point-equal'])){ ?>
                    <!--积分购买额度-->
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['point_exchange']?></th>
                        <td><input type="hidden" id="point" value="<?php
                            echo $configs['point-equal'];
                            ?>"> <input style="width:200px;" type="text" readonly name="data[Product][point_fee]" id="point_fee" value="<?php
                            $product_info['Product']['shop_price']=isset($product_info['Product']['shop_price'])?$product_info['Product']['shop_price']:0;
                            echo $configs['point-equal']*$product_info['Product']['shop_price'];
                            ?>" /></td>
                    </tr>
                <?php } ?>
                <!--商品冻结库存数量-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld["prod_freeze_qty"]?></th>
                    <td><input type="text" style="width:200px;" id="num" class="num" name="data[Product][frozen_quantity]" value="<?php echo isset($product_info['Product']['frozen_quantity'])?$product_info['Product']['frozen_quantity']:'0';?>" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /></td>
                </tr>
                <!--库存警告数量-->
                <?php if(!empty($configs['rank']) && $configs['rank']>1){?>
                    <tr>
                        <th><?php echo $ld['quantity_warning_num']?></th>
                        <td><select name="data[Product][warn_style]" style="width: auto;">
                                <option value="0" <?php if(isset($product_info['Product']['warn_style'])&&$product_info['Product']['warn_style']=="0"){echo "selected";}?> ><?php echo $ld["system_setting"]?></option>
                                <option value="1" <?php if(isset($product_info['Product']['warn_style'])&&$product_info['Product']['warn_style']=="1"){echo "selected";}?>><?php echo $ld["custom"]?></option>
                            </select>
                            <input type="text" style="width:auto;" name="data[Product][warn_quantity]" value="<?php echo isset($product_info['Product']['warn_quantity'])?$product_info['Product']['warn_quantity']:'';?>" /></td>
                    </tr>
                <?php }?>
                <!--最小购买数量-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['min_purchase_qty']?></th>
                    <td><input type="text" style="width:200px;" id="num" class="num" name="data[Product][min_buy]" value="<?php echo isset($product_info['Product']['min_buy'])?$product_info['Product']['min_buy']:'1';?>" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /></td>
                </tr>
                <!--商品库存数量-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['max_purchase_qty']?></th>
                    <td><input type="text" style="width:200px;" id="num" class="num" name="data[Product][max_buy]" value="<?php echo isset($product_info['Product']['max_buy'])?$product_info['Product']['max_buy']:'999';?>" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /></td>
                </tr>
                <!--加入推荐-->
                <tr>
                    <th><?php echo $ld['add_recommend']?></th>
                    <td><label style="padding:0 2rem;" class="am-checkbox am-success"><input type="checkbox" name="data[Product][recommand_flag]" value="1" data-am-ucheck <?php if(isset($product_info['Product']['recommand_flag'])&&$product_info['Product']['recommand_flag']==1){echo "checked";}?> /><?php echo $ld['recommend']?></label></td>
                </tr>
                <!--能作为普通商品销售-->
                <tr>
                    <th><?php echo $ld['as_general_product']?></th>
                    <td><label style="padding:0 2rem;" class="am-checkbox am-success"><input type="checkbox" name="data[Product][alone]" value="1" data-am-ucheck <?php if(!isset($product_info['Product']['alone'])||(isset($product_info['Product']['alone'])&&$product_info['Product']['alone']==1)){echo "checked";}?> /><?php echo $ld['toggle_for_allow_sale']?></label></td>
                </tr>
                <tr >
                    <th style="padding-top:12px" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['label_01']?>
                        <div style="display:none;" id="div_img_label_tag_hidden"></div>
                    </th>
                </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><p class="div_img_label_tag"><?php
                                if(isset($tag_infos[$v['Language']['locale']]) && sizeof($tag_infos[$v['Language']['locale']])>0){
                                    foreach($tag_infos[$v['Language']['locale']] as $t){?>
                                        <span class="taglist"><span onclick="removetags(this)" class="am-icon-minus am-fl"></span><span class="am-fl"><input style="padding-top:9px;padding-bottom:9px;" type="text" value="<?php echo $t;?>" name="data[TagI18n][<?php echo $k;?>][name][]" style="margin-bottom: 10px;width: 100px;"></span><span class="am-cf"></span></span>
                                <?php }} ?><input class="am-btn am-btn-success am-radius am-btn-sm" onclick="addtags(this,'data[TagI18n][<?php echo $k;?>][name][]')" style="float:left;" type="button" name="data[TagI18n][<?php echo $k;?>][name][]" value="<?php echo $ld['add']?>" /><input type="text" maxlength="10" style="width:100px;float:left;height:32px;" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></p>
                        </td>
                    </tr>
                    <?php }}?>
                <!--商品关键词--->
                <tr>
                    <th  style="padding-top:14px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['product_keywords']?></th>
                </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><input type="text" style="width:200px;float:left;" name="data[ProductI18n][<?php echo $k;?>][meta_keywords]" value="<?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['meta_keywords']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                    </tr>
                    <?php }}?>
                <!--商品简单描述--->
                <tr>
                    <th  rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['meta_description']?></th>
                </tr>
                <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                <tr>
                    <td><textarea style="width:400px;float:left;" name="data[ProductI18n][<?php echo $k;?>][meta_description]"><?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['meta_description']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" ><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                </tr>
                <?php }}?>
                <!--商品网站网址--->
                <tr>
                    <th  style="padding-top:25px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['product_web_site']?></th>
                </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><input type="text" style="width:200px;float:left;" name="data[ProductI18n][<?php echo $k;?>][api_site_url]" value="<?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['api_site_url']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                    </tr>
                    <?php }}?>
                <!--购物车快捷网址--->
                <tr>
                    <th  style="padding-top:12px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['cart_url']?></th>
                </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><input  type="text" style="width:400px;float:left;" name="data[ProductI18n][<?php echo $k;?>][api_cart_url]" value="<?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['api_cart_url']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                    </tr>
                    <?php }}?>
                <tr>
                    <th  style="padding-top:15px;" rowspan="2"><?php echo $ld['product'].$ld['video'];?>Id</th>
                </tr>
                <tr>
                    <td><input type="text" style="width:400px;" name="data[Product][video]" value="<?php echo isset($product_info['Product']['video'])?$product_info['Product']['video']:'';?>" /></td>
                </tr>
                <!--商家备注--->
                <tr>
                    <th style="padding-top:23px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['shop_remark']?></th>
                </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><textarea style="width:400px;float:left;"" name="data[ProductI18n][<?php echo $k;?>][seller_note]"><?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['seller_note']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="margin-top:10px;" ><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                    </tr>
                    <?php }}?>
                <!--发货备注--->
                <?php if(!empty($configs['rank']) && $configs['rank']>1){?>
                    <tr>
                        <th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['delivery_remark']?></th>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <td><textarea style="width:400px;float:left;" name="data[ProductI18n][<?php echo $k;?>][delivery_note]"><?php echo isset($product_info['ProductI18n'][$v['Language']['locale']])?$product_info['ProductI18n'][$v['Language']['locale']]['delivery_note']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                            <?php }}?>
                    </tr>
                <?php }?>
                <!--商品关联文件--->
                <tr>
                    <th  style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['commodity_file'];?></th>
                </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td>
                            <input type="text" style="width:200px;float:left;margin-right:5px;" id="ProductDownload<?php echo $k;?>" name="data[ProductDownload][<?php echo $k;?>][url]" value="<?php echo isset($product_info['ProductDownload'][$v['Language']['locale']])?$product_info['ProductDownload'][$v['Language']['locale']]['url']:'';?>"><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?>&nbsp;
                            <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" data-am-modal="{target: '#product_file', closeViaDimmer: 0, width: 400, height: 275}" onclick="show_upload_div(<?php echo $k;?>)" value="<?php echo $ld['file_uplaod']?>" />
                        </td>
                    </tr>
                    <?php }}?>
            </table>
        </div>
    </div>
</div>



<?php if($option_type_id==0){ ?>
    <div id="pro_att" class="am-panel am-panel-default" >
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['product_attribute']; ?></h4>
        </div>
        <div id="product_attribute" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <?php if(empty($product_type_list) && strip_tags($products_attr_html)==""){echo "<tr><td class='infotips'>".$ld['not_product_attributes']."!</td></tr>";}else{ ?>
                        <?php if(!empty($product_type_list)){?>
                            <tr>
                                <td colspan="2">
                                    <select data-am-selected name="data[Product][product_type_id]" id="product_type" onchange="getAttrList(<?php echo (empty($product_info['Product']['id'])?0:$product_info['Product']['id']);?>)" style="margin-left:20px;">
                                        <option value=""><?php echo $ld['please_select']?></option>
                                        <?php echo $product_type_list;?>
                                    </select>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                </table>
                <div id="productsAttrdiv"></div>
                <script type="text/javascript">
                    getAttrList(<?php echo (empty($product_info['Product']['id'])?0:$product_info['Product']['id']);?>);
                </script>
            </div>
        </div>
    </div>
<?php }?>
<?php if($is_sku==false&&$option_type_id==2){?>
    <div id="sales_att" class="am-panel am-panel-default" >
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['sales_attribute']; ?></h4>
        </div>
        <div id="sales_attribute" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <?php if(empty($product_type_list) && strip_tags($products_attr_html)==""){echo "<tr><td class='infotips'>".$ld['not_product_attributes']."!</td></tr>";}else{ ?>
                        <?php if(!empty($product_type_list)){?>
                            <tr>
                                <td colspan="2">
                                    <select data-am-selected name="data[Product][product_type_id]" id="product_sku_type"  onchange="getSkuType(<?php echo (empty($product_info['Product']['id'])?0:$product_info['Product']['id']);?>,this)">
                                        <option value=""><?php echo $ld['please_select']?></option>
                                        <?php echo $product_type_list;?>
                                    </select>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                </table>
                <div id="productSku_list"></div>
            </div>
        </div>
    </div>
<?php }?>
<?php if($option_type_id==1){?>
    <div id="pack_pro" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['package_product']?></h4>
        </div>
        <div id="package_product" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <b><?php echo $ld['suit'];?></b>
                <table class="am-table">
                    <tr>
                        <td>
                            <div id="package_table">
                                <table style="width:100%;" class="tablelist">
                                    <thead>
                                    <tr style="background:#E9F8D9;">
                                        <th id="taozhuang"><?php echo $ld['product'].$ld['picture'];?></th>
                                        <th><?php echo $ld['code']?></th>
                                        <th style="width:40%"><?php echo $ld['name']?></th>
                                        <th><?php echo $ld['price']?></th>
                                        <th><?php echo $ld['quantity']?></th>
                                        <th><?php echo $ld['app_qty']?></th>
                                        <th><?php echo $ld['sort']?></th>
                                        <th><?php echo $ld['operate']?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  if(isset($package_products)&&!empty($package_products)){foreach($package_products as $pk=>$pv){?>
                                        <tr >
                                            <td><a target="_blank" href="/admin/products/view/<?php echo $pv['PackageProduct']['package_product_id']?>"><img style="margin:5px 0 5px 10px;" width='45px' height='45px' src="<?php echo $pv['PackageProduct']['img']?>"/></a></td>
                                            <td><?php echo $pv['PackageProduct']['package_product_code']?>
                                                <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_id]" value="<?php echo $pv['PackageProduct']['package_product_id'];?>">
                                                <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_code]" value="<?php echo $pv['PackageProduct']['package_product_code'];?>">
                                            </td>
                                            <td style="width:40%"><?php if($svshow->operator_privilege('products_edit')){?><span onclick="update_packageproduct_name(this)"><?php } echo $pv['PackageProduct']['package_product_name'];?></span>
                                                <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_name]" value="<?php echo $pv['PackageProduct']['package_product_name'];?>">
                                            </td>
                                            <td><?php echo $pv['PackageProduct']['price']?></td>
                                            <td><?php echo $pv['PackageProduct']['quantity']?></td>
                                            <td>
                                                <?php if($svshow->operator_privilege('products_edit')){?><span onclick="update_package_qty(this)"><?php } echo $pv['PackageProduct']['package_product_qty'];?></span>
                                                <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_qty]" value="<?php echo $pv['PackageProduct']['package_product_qty'];?>">
                                            </td>
                                            <td><?php if(count($package_products)==1){echo "-";}elseif($pk==0){?>
                                                    <a class="up" onclick="up(this)" style="color:#cc0000;display:none;">&#9650;</a>&nbsp;<a class="down" onclick="down(this)">&#9660;</a>
                                                <?php }elseif($pk==(count($package_products)-1)){?>
                                                    <a class="up" onclick="up(this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a style="display:none;" class="down" onclick="down(this)">&#9660;</a>
                                                <?php }else{?>
                                                    <a onclick="up(this)" class="up" style="color:#cc0000;">&#9650;</a>&nbsp;<a class="down" onclick="down(this)">&#9660;</a>
                                                <?php }?>
                                                <input class="pkg_orderby" type="hidden" name="data[PackageProduct][<?php echo $pk;?>][orderby]" value="<?php echo $pk;?>">
                                            </td>
                                            <td><a href="javascript:void(0);" id="<?php echo $pv['PackageProduct']['package_product_id']?>" onclick="del_package(this)" class="del_package_product am-btn am-btn-default am-text-danger am-radius am-btn-sm am-radius"><?php echo $ld["delete"]?></a></td>
                                        </tr>
                                    <?php }}else{?>
                                        <tr><td></td><td colspan="5" align="center"><?php echo $ld['not_add_package']?></td><td></td></tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="hidden" id="show_package_products" value="<?php echo isset($show_package_products)?$show_package_products:"";?>" /><input class="am-btn am-btn-success am-radius am-btn-sm" id="add_package" data-am-modal="{target:'#package_pro',closeViaDimmer:0,width:700}" type="button" value="<?php echo $ld['add'].$ld['suit'];?>">&nbsp;&nbsp;<?php echo $ld['add_five_package']?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php }?>

<!-- 商品、文章关联 start -->

<div id="related_product_article" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title"><?php echo $ld['related_product_article']?></h4>
    </div>
    <div class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
            <!-- 关联商品 start -->
            <table class="am-table">
                <tr>
                    <td colspan="3"><?php echo $this->element('category_tree');?>
                        <!--分类模块-->
                        <?php echo $this->element('brand_tree');?>
                        <!--品牌模块-->
                        <input style="width:200px;float:left;margin-right:5px;margin-top:5px;" type="text" name="product_keyword" id="product_keyword" /> <input style="margin-top:5px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" onclick="searchProducts();" /></td>
                </tr>
            </table>
            <div class="am-form-group">
                <div class="am-u-lg-4 am-u-md-4 am-hide-sm-only am-text-center"><label><?php echo $ld['option_products']?></label></div>
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-text-center">
                    <label style="margin-right:10px;"><input type="radio" name="is_single[]" value="0" checked/><?php echo $ld['unidirectional']?></label>
                    <label><input type="radio" name="is_single[]" value="1"/><?php echo $ld['each_other_ralation']?></label>
                </div>
                <div class="am-u-lg-4 am-u-md-4 am-hide-sm-only am-text-center"><label><?php echo $ld['product_products']?></label></div>
                <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
                    <label class='am-show-sm-only'><?php echo $ld['option_products']?></label>
                    <div id="product_select" class="related_dt"></div>
                </div>
                <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
                    <label class='am-show-sm-only'><?php echo $ld['product_products']?></label>
                    <div id="relative_product">
                        <?php if(isset($product_relation_product) && sizeof($product_relation_product)>0)foreach($product_relation_product as $k=>$v){
                            if(isset($v['ProductRelation'])){?>
                                <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 relative_product_data">
                                    <div class="am-u-lg-10 am-u-md-10 am-u-sm-10"><?php echo $v['ProductRelation']['name']; ?></div>
                                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                        <span class="am-icon-close am-no" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)" onclick="drop_product_relation_product('<?php echo $v['ProductRelation']['id'];?>','<?php echo isset($product_info["Product"]["id"])?$product_info["Product"]["id"]:'0';?>')"></span>
                                    </div>
                                </div>
                            <?php } }?>
                    </div>
                </div>
            </div>
            <!-- 关联商品 end -->
            <hr >
            <!-- 关联文章 start -->
            <table class="am-table">
                <tr>
                    <td colspan="3"><?php echo $this->element('category_tree_articles');?>
                        <!--分类模块-->
                        <input type="text" style="margin-right:5px;width:200px;float:left;margin-top:5px;" name="article_keyword" id="article_keyword" /> <input class="am-btn am-btn-success am-radius am-btn-sm" style="margin-top:5px;" type="button" value="<?php echo $ld['search']?>" onclick="searchArticles();" /></td>
                </tr>
            </table>
            <div class="am-form-group">
                <div class="am-u-lg-4 am-u-md-4 am-hide-sm-only am-text-center"><label><?php echo $ld['option_article']?></label></div>
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-text-center">
                    <label style="margin-right:10px;"><input type="radio" name="is_single2[]" value="0" checked /><?php echo $ld['unidirectional']?></label>
                    <label><input type="radio" name="is_single2[]" value="1" /><?php echo $ld['each_other_ralation']?></label>
                </div>
                <div class="am-u-lg-4 am-u-md-4 am-hide-sm-only am-text-center"><label><?php echo $ld['product_articles']?></label></div>
                <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
                    <label class='am-show-sm-only'><?php echo $ld['option_article']; ?></label>
                    <div id="article_select" class="related_dt"></div>
                </div>
                <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
                    <label class='am-show-sm-only'><?php echo $ld['product_articles']?></label>
                    <div id="relative_article">
                        <?php if(isset($product_relation_articles) && sizeof($product_relation_articles)>0)foreach($product_relation_articles as $k=>$v){
                            if (isset($v['ProductArticle'])){?>
                                <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 relative_article_data">
                                    <div class="am-u-lg-10 am-u-md-10 am-u-sm-10"><?php echo $v['ProductArticle']['title']; ?></div>
                                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                        <span class="am-icon-close am-no" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)" onclick="drop_product_relation_article('<?php echo $v['ProductArticle']['id'];?>','<?php echo isset($product_info["Product"]["id"])?$product_info["Product"]["id"]:0;?>')"></span>
                                    </div>
                                </div>
                            <?php } }?>
                    </div>
                </div>
            </div>
            <!-- 关联文章 end -->
        </div>
    </div>
</div>

<!-- 商品、文章关联 end -->
</div>
<?php echo $form->end();?>

<!------------------------   弹窗部分   ----------------------------->
<div class="am-modal am-modal-no-btn" tabindex="-1" name="brand" id="brand">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['product_add_brand']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <form id='catform2' method="POST">
            <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                <input name="data1[BrandI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
                <?php break;}}?>
            <div class="am-modal-bd">
                <table class="am-table">
                    <tr>
                        <th><?php echo $ld['brand_name']?>：</th>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <td><input id="brand_name_<?php echo $v['Language']['locale'];?>" name="data1[BrandI18n][<?php echo $k;?>][name]" type="text" value=""><em>*</em></td>
                            <?php break;}}?>
                    </tr>
                    <tr>
                        <th rowspan="2"><?php echo $ld["brand_code"]?>：</th>
                    </tr>
                    <tr>
                        <td><input id="BrandCode" name="BrandCode"><em>*</em></td>
                    </tr>
                </table>
                <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" name="changeDomainRankButton" value="<?php echo $ld['confirm']?>" onclick="javascript:doinsertbrand();">
            </div>
        </form>
    </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" name="productattribute" id="productattribute">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['add_attribute_type']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <form id='catform5' method="POST">
            <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                <input name="data1[ProductTypeI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
            <?php }}?>
            <div class="am-modal-bd">
                <table class="am-table">
                    <tr>
                        <th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['product_type_name']?>：</th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input id="attribute_name_<?php echo $v['Language']['locale'];?>" name="data1[ProductTypeI18n][<?php echo $k;?>][name]" type="text" value=""><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        
                    <?php }}?>
                    <tr>
                        <th rowspan="2"><?php echo $ld["attribute_code"]?>：</th>
                    </tr>
                    <tr>
                        <td><input id="attributetypeCode" name="attributetypeCode"></td>
                    </tr>
                    <tr>
                        <th rowspan="2"><?php echo $ld["attribute_group"]?>：</th>
                    </tr>
                    <tr>
                        <td><input id="attributetypegroupCode" name="attributetypegroupCode"></td>
                    </tr>
                </table>
                <input type="button"  name="changeDomainRankButton" value="<?php echo $ld['confirm']?>" onclick="javascript:doinsertattribute();">
            </div>
        </form>
    </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" name="productcattype" id="productcattype">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['add_category_type']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='catform3' method="POST">
                <table class="am-table">
                    <tr>
                        <th rowspan="2"><?php echo $ld['category_type_name']?>：</th>
                    </tr>
                    <tr>
                        <td style="text-align:left;"><input id="CattypeName" name="CattypeName" type="text" value=""><em>*</em></td>
                    </tr>
                    <tr>
                        <th rowspan="2"><?php echo $ld["category_type_code"]?>：</th>
                    </tr>
                    <tr>
                        <td style="text-align:left;"><input id="CattypeCode" name="CattypeCode"></td>
                    </tr>
                </table>
                <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" name="changeDomainRankButton" value="<?php echo $ld['confirm']?>" onclick="javascript:doinsertcattype();">
            </form>
        </div>
    </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" name="placement" id="package_pro" style="overflow-y:auto;overflow-x:hidden;max-height:450px;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['add'].' '.$ld['package_product'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='packageform' method="POST" action="/admin/products/add_package_product">
                <div id="change_package" style="width:600px;text-align: left;margin:5px 0 0;">
                    <?php echo $this->element('category_package_tree');?>
                    <!--品牌模块-->
                    <input type="text" name="product_package_keyword" id="product_package_keyword" /> <input type="button" value="<?php echo $ld['search']?>" class="am-btn am-btn-success am-radius am-btn-sm" id="search_package" onclick="get_page_pro()"/>
                </div>
                <div id="select_package"></div>
                <div style="clear:both"></div>
                <div id="show_package">
                    <div style="float:left;width:100px;height: 26px;line-height: 26px;"><?php echo $ld["added"].$ld["product"]?>:
                        <input id="selected_package" name="selected_package" type="hidden" value="<?php echo isset($show_package_products)?$show_package_products:"";?>"></div>
                    <div id="show_select_package" style="float:left">
                        <!--套装商品弹窗显示-->
                        <?php if(isset($package_products)&&!empty($package_products)){foreach($package_products as $pk2=>$pv2){?>
                            <div style="clear:both;width:425px;height:26px;line-height: 26px;">
                                <span style="float:left;"><?php echo $pv2['PackageProduct']['package_product_name']?></span>
                    <span style="float:right;">
                        <a href="javascript:void(0);" id="del_selected<?php echo $pv2['PackageProduct']['package_product_id']?>" class="del_selected" ><?php echo $ld['delete']?></a>
                    </span>
                            </div>
                        <?php }}?>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <input type="hidden" name='product_id' id='product_id' value="<?php echo isset($product_info["Product"]["id"])?$product_info["Product"]["id"]:"";?>">
                <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" id="save_package"  onclick="submit_package()" name="save_package" value="<?php echo $ld['submit']?>">
            </form>
        </div>
    </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" name="productcat" id="productcat">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['add_category']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='catform1' method="POST">
                <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <input name="data1[CategoryProductI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
                    <?php break;}}?>
                <table class="am-table">
                    <tr>
                        <th rowspan=2><?php echo $ld['higher_category']?>：</th>
                    </tr>
                    <tr>
                        <td><select name="parent_id" id="product_category_parent_id">
                                <option value="0"><?php echo $ld['root']?></option>
                                <?php if(isset($category_tree) && sizeof($category_tree)>0){?>
                                    <?php foreach($category_tree as $first_k=>$first_v){?>
                                        <option value="<?php echo $first_v['CategoryProduct']['id'];?>" ><?php echo $first_v['CategoryProductI18n']['name'];?></option>
                                        <?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
                                            <?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
                                                <option value="<?php echo $second_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
                                                <?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
                                                    <?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
                                                        <option value="<?php echo $third_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
                                                    <?php }}}}}}?>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['category_name']?>：</th>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <td><input id="productcat_name_<?php echo $v['Language']['locale'];?>" name="data1[CategoryProductI18n][<?php echo $k;?>][name]" type="text" value=""><em>*</em></td>
                            <?php break;}}?>
                    </tr>
                </table>
                <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" name="changeDomainRankButton" value="<?php echo $ld['confirm']?>" onclick="javascript:doinsertproductcat();">
            </form>
        </div>
    </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" name="product_file" id="product_file">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['fast_upload_pictures']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <input type="hidden" id="upload_input" name="upload_input" value="">
            <form id='upload_file_form' method="POST" enctype="multipart/form-data">
                <table class="am-table">
                    <tr>
                        <th><?php echo $ld['file_uplaod']?></th>
                        <td>
                            <input type="file" style="width:206px" size="40" name="product_file_url" id="product_file_url" value="" onchange="checkUploadFile(this,'product_file_url')"/>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td class="am-text-left">
                            <input class="am-btn am-btn-success am-radius am-btn-sm" style="margin-left:60px;" type="button" name="UploadFileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:doupload_file();">
                        </td>
                    </tr>
                </table>
                <p>
                    <?php echo $ld['file_optional_format']?>:<?php echo empty($file_types)? '':$file_types; ?>
                </p>
                <p>
                    <?php echo $ld['single_size_exceed']?>
                </p>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
//修改套装商品名称
function update_packageproduct_name(obj){
    var nameTd = $(obj);
    if (nameTd.children("input").length > 0) {
        //如果当前td中已包含有文本框元素，则不执行click事件
        return false;
    }
    var name_text = nameTd.html();
    nameTd.html("");
    var inputOjb = $("<input type='text' maxlength='60'/>").css("border-width", "0").width("100%").val(name_text).appendTo(nameTd);
    inputOjb.trigger("focus").trigger("select");
    //去掉文本框的点击事件,(javascript事件是冒泡型的)
    inputOjb.click(function() {
        return false;
    });
    //处理文本框上回车和ESC按键的操作
    inputOjb.keyup(function(event) {
        //获取当前按下的键盘的键值
        // 不同的按键可以做不同的事情
        var keyCode = event.which;
        //处理回车键 ,不同的浏览器的keycode不同
        if (keyCode == 13) {
            //保存当前输入的内容
            var inputText = $(this).val();
            if(document.getElementById("product_name_chi")){
                var name = inputText.replace(/([\u0391-\uFFE5])/ig,'11');
                if(name.length>60){
                    nameTd.html(name_text);
                    nameTd.parent().find("input[name*='package_product_name']").val(name_text);
                    return false;
                }
            }
            nameTd.html(inputText);
            nameTd.parent().find("input[name*='package_product_name']").val(inputText);
        }
        //处理ESC键的操作
        if (keyCode == 27) {
            //将当前TD的内容还原成name_text
            nameTd.html(name_text);
        }
    });
    inputOjb.blur(function(){
        var inputText = $(this).val();
        if(inputText!=""){
            if(document.getElementById("product_name_chi")){
                var name = inputText.replace(/([\u0391-\uFFE5])/ig,'11');
                if(name.length>60){
                    alert("商品名称过长，最长60字符!");
                    nameTd.html(name_text);
                    nameTd.parent().find("input[name*='package_product_name']").val(name_text);
                    return false;
                }
            }
            nameTd.html(inputText);
            nameTd.parent().find("input[name*='package_product_name']").val(inputText);
        }else{
            nameTd.html(name_text);
            nameTd.parent().find("input[name*='package_product_name']").val(name_text);
        }
    })
}

//修改套装商品数量
function update_package_qty(obj){
    var qtyTd = $(obj);
    if (qtyTd.children("input").length > 0) {
        //如果当前td中已包含有文本框元素，则不执行click事件
        return false;
    }
    var qty_text = qtyTd.html();
    qtyTd.html("");
    var inputOjb = $("<input type='text' />").css("border-width", "0").width("50px").val(qty_text).appendTo(qtyTd);
    inputOjb.trigger("focus").trigger("select");
    //去掉文本框的点击事件,(javascript事件是冒泡型的)
    inputOjb.click(function() {
        return false;
    });
    //处理文本框上回车和ESC按键的操作
    inputOjb.keyup(function(event) {
        //获取当前按下的键盘的键值
        // 不同的按键可以做不同的事情
        var keyCode = event.which;
        //处理回车键 ,不同的浏览器的keycode不同
        if (keyCode == 13) {
            //保存当前输入的内容
            var inputText = $(this).val();
            qtyTd.html(inputText);
            qtyTd.parent().find("input[name*='package_product_qty']").val(inputText);
        }
        //处理ESC键的操作
        if (keyCode == 27) {
            //将当前TD的内容还原成qty_text
            qtyTd.html(qty_text);
        }
    });
    inputOjb.blur(function(){
        var inputText = $(this).val();
        if(inputText!=""){
            qtyTd.html(inputText);
            qtyTd.parent().find("input[name*='package_product_qty']").val(inputText);
        }else{
            qtyTd.html(qty_text);
            qtyTd.parent().find("input[name*='package_product_qty']").val(qty_text);
        }
    })
}

//套装商品上下排序
function up(obj) {
    var TRcount=$("#package_table .tablelist tbody tr").length-1;
    var objParentTR = $(obj).parent().parent();//当前tr
    var prevTR = objParentTR.prev();//上一个tr
    if (prevTR.length > 0) {
        //上一个tr排序加1
        var prev_orderby=prevTR.find("input[name*='orderby']");
        prev_orderby.val(parseInt(prev_orderby.val())+1);
        if(prev_orderby.val()=="0"){
            prevTR.find(".up").hide();
            prevTR.find(".down").show();
        }else if(prev_orderby.val()==TRcount){
            prevTR.find(".up").show();
            prevTR.find(".down").hide();
        }else{
            prevTR.find(".up").show();
            prevTR.find(".down").show();
        }
        prevTR.insertAfter(objParentTR);
        //当前tr排序减1
        var this_orderby=$(obj).parent().find("input[name*='orderby']");
        this_orderby.val(parseInt(this_orderby.val())-1);
        if(this_orderby.val()=="0"){
            objParentTR.find(".up").hide();
            objParentTR.find(".down").show();
        }else if(this_orderby.val()==TRcount){
            objParentTR.find(".up").show();
            objParentTR.find(".down").hide();
        }else{
            objParentTR.find(".up").show();
            objParentTR.find(".down").show();
        }
    }
}

function down(obj) {
    var TRcount=$("#package_table .tablelist tbody tr").length-1;
    var objParentTR = $(obj).parent().parent();//当前tr
    var nextTR = objParentTR.next();//下一个tr
    if (nextTR.length > 0) {
        //下一个tr排序减1
        var next_orderby=nextTR.find("input[name*='orderby']");
        next_orderby.val(parseInt(next_orderby.val())-1);
        if(next_orderby.val()=="0"){
            nextTR.find(".up").hide();
            nextTR.find(".down").show();
        }else if(next_orderby.val()==TRcount){
            nextTR.find(".up").show();
            nextTR.find(".down").hide();
        }else{
            nextTR.find(".up").show();
            nextTR.find(".down").show();
        }
        nextTR.insertBefore(objParentTR);
        //当前tr排序加1
        var this_orderby=$(obj).parent().find("input[name*='orderby']");
        this_orderby.val(parseInt(this_orderby.val())+1);
        if(this_orderby.val()=="0"){
            objParentTR.find(".up").hide();
            objParentTR.find(".down").show();
        }else if(this_orderby.val()==TRcount){
            objParentTR.find(".up").show();
            objParentTR.find(".down").hide();
        }else{
            objParentTR.find(".up").show();
            objParentTR.find(".down").show();
        }
    }
}

//套装商品上下排序 end
//编辑页上的删除套装商品
function del_package(obj){
    var id=$(obj).attr('id');
    var del_id="del_selected"+id;
    $(obj).parent().parent().remove();
    var i=0
    $(".pkg_orderby").each(function(){
        $(obj).val(i);
        i++;
    });
    $("#"+del_id).click();
}

//360旋转JS
$(document).ready(function() {
    $('#start_presentation').click(function(){
        var presentation = new javascriptViewer($('#product_image_x'),{
            total_frames:<?php echo $product_Rotation_img_count; ?>,
            target_id:'image_holder_x'
        });
        presentation.start();
        setCSS();
    });

    function setCSS(){
        alert('加载中，请稍后...');
        $("#image_holder_x div div").each(function(ii,vv){
            $(this).css("position","relative");
            $(this).find("div").each(function(iii,vvv){
                $(this).css("position","relative");
                $(this).find("img").css("position","relative");
            })
        })
    }
});

function delete_img(product_code){
    if (confirm("<?php echo $ld['confirm_delete'];?>")){
        $.ajax({
            cache: true,
            type: "POST",
            url:"/admin/products/delete_img",
            data:{"product_code":product_code},
            success: function(data) {
                var result= JSON.parse(data);
                if(result['flag']==1){
                    $("#product_image_x").attr("src","<?php echo $configs['shop_default_img']; ?>");
                    alert("<?php echo $ld['deleted_success'];?>");
                }else{
                    alert("<?php echo $ld['delete_failure'];?>");
                }
            }
        });
    }
}

//货号
function huohao(){
    var url =encodeURI(admin_webroot+"product_code_creates");
    window.open(url);
}

function chaxiao(){
    var sp=0;
    if(document.getElementById("shop_price").value!='')
        sp=parseFloat(document.getElementById("shop_price").value);
    if(parseFloat(document.getElementById("promote_price").value)>sp){
        alert("<?php echo $ld['sales_higher_original_price']?>");
        document.getElementById("promote_price").value=sp;
    }
}

function cha2(){
    if(document.getElementById("product_name_chi")){
        var name = document.getElementById("product_name_chi").value.replace(/([\u0391-\uFFE5])/ig,'11');
        if(name.length>60){
            alert("商品名称过长，最长60字符!");
            return false;
        }
    }
    if(document.getElementById("num").value=='')
        alert("<?php echo $ld['have_no_filled_product_num']?>");
    if(document.getElementById("shop_price").value=='')
        alert("<?php echo $ld['have_no_filled__product_price']?>");
    if(document.getElementById("num").value != ''){
        var c=document.getElementById("num").value;
        var tiao1=c>=0;
        if(tiao1==false){
            alert("<?php echo $ld['fill_product_number_error']?>");
            return false;
        }
    }
    if(document.getElementById("promotion_status").checked){
        var a=document.getElementById("start_date").value
        var b=document.getElementById("end_date").value
        var d1=new Date(a.replace(/\-/g,'/'));
        var d2=new Date(b.replace(/\-/g,'/'));
        var tiao=d1<d2;
        if(tiao==false ){
            alert("<?php echo $ld['fill_promotion_number_error']?>");
            return false;
        }
    }
    var product_code = document.getElementById("product_num").value;
    //货号长度不能大与20
    if(product_code.length>20){
        alert("商品货号不能大于20位数字");
        return false;
    }
    var material_code_flag=false;
    var material_code_length=$("select.material_code").length;
    if(material_code_length>0){
        $("select.material_code").each(function(){
            var material_value=$(this).val();
            if(material_value=="0"){
                material_code_flag=true; 
            }
        });
    }
    if(material_code_flag){
        alert("请选择材料单位");
        return false;
    }
    return true;
}
var checkproCodeFlag=true;

//检验货号
function checkProductCode(){
    var product_code = document.getElementById("product_num").value;
    //货号长度不能大与20
    if(product_code.length>20){
        alert("商品货号不能大于20位数字");
    }else{
        var pId = document.getElementById("productid").value;
        if(product_code!=""){
            checkproCodeFlag=false;
            var sUrl = "/admin/products/select_product_code/";//访问的URL地址
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {product_code:product_code,pId:pId},
                success: function (result) {
                    if(result.type==1){
                        document.getElementById("product_num_h").value=1;
                        alert(result.message);
                        document.getElementById("product_num").focus();
                    }else{
                        document.getElementById("product_num_h").value=0;
                        if(result.code){
                            document.getElementById("product_num").value=result.code;
                        }
                        alert(result.message);
                    }
                    checkproCodeFlag=true;
                }
            });
        }
    }
}

function checkrouteurl (){
    var route_url = document.getElementById("Route_url").value;
    if(route_url!=""){
        var rUrl = "/admin/routes/select_route_url/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: rUrl,
            dataType: 'json',
            data: {route_url:route_url},
            success: function (result) {
                if(result.type==1){
                    alert(result.message);
                    document.getElementById("route_url_h").value=1;
                }else{
                    document.getElementById("route_url_h").value=0;
                }
            }
        });
    }
}

//快速添加属性
function doinsertattribute(){
    var attrname=document.getElementById("attribute_name_"+backend_locale).value;
    if(attrname==""){
        alert("请输入商品属性组名称");
    }else{
        var sUrl = "/admin/productstypes/doinsertattribute/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {id: "catform5", useDisabled:true},
            success: function (result) {
                if(result.flag==1){
                    $('#product_attribute_id').html(result.arrtibute);
                    btnClose();
                    document.getElementById("attribute_name_"+backend_locale).value="";
                    document.getElementById("attributetypeCode").value="";
                    document.getElementById("attributetypegroupCode").value="";
                }
                if(result.flag==2){
                    alert(result.message);
                }
            }
        });
    }
}

//快速添加分类
function doinsertproductcat(){
    var typename=document.getElementById("productcat_name_"+backend_locale).value;
    if(typename==""){
        alert("请输入分类名称");
    }else{
        var sUrl = "/admin/product_categories/doinsertproductcat/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: $('#catform1').serialize(),
            success: function (result) {
                if(result.flag==1){
                    $("#product_category_id option").remove();
                    $("#product_category_parent_id option").remove();
                    var opt = document.createElement("OPTION");
                    opt.text = result.select_categories;
                    opt.value = 0;
                    $("#product_category_type_id").append(opt);
                    $("#product_category_parent_id").append(opt);
                    $(result.cat).each(function(index,item){
                        var opt = document.createElement("OPTION");
                        opt.text = item['CategoryProductI18n']['name'];
                        opt.value = item['CategoryProduct']['id'];
                        $("#product_category_parent_id").append(opt);
                        if(opt.value==result.last_categor_id){
                            opt.selected=true;
                        }
                        $("#product_category_id").append(opt);
                        if(typeof(item['SubCategory'])!=null){
                            $(item['SubCategory']).each(function(index,item2){
                                var opt = document.createElement("OPTION");
                                opt.text = "|--"+item2['CategoryProductI18n']['name'];
                                opt.value = item2['CategoryProduct']['id'];
                                $("#product_category_parent_id").append(opt);
                                if(opt.value==result.last_categor_id){
                                    opt.selected=true;
                                }
                                $("#product_category_id").append(opt);
                                if(typeof(item2['SubCategory'])!=null){
                                    $(item2['SubCategory']).each(function(index,item3){
                                        var opt = document.createElement("OPTION");
                                        opt.text = "|----"+item3['CategoryProductI18n']['name'];
                                        opt.value = item3['CategoryProduct']['id'];
                                        $("#product_category_parent_id").append(opt);
                                        if(opt.value==result.last_categor_id){
                                            opt.selected=true;
                                        }
                                        $("#product_category_id").append(opt);
                                    })
                                }
                                
                            })
                        }
                    })
                    $("#product_category_id").trigger('changed.selected.amui');
                    $("#productcat .am-close").click();
                    document.getElementById("productcat_name_"+backend_locale).value="";
                }
                if(result.flag==2){
                    alert(result.message);
                }
            }
        });
    }
}

//快速添加类目
function doinsertcattype(){
    var typename=document.getElementById("CattypeName").value;
    if(typename==""){
        alert("请输入类目名称");
    }else{
        var sUrl = "/admin/category_types/doinsertcattype/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data:$('#catform3').serialize(),
            success: function (result) {
                if(result.flag==1){
                    $("#product_category_type_id option").remove();
                    var opt = document.createElement("OPTION");
                    opt.text = result.select_categories;
                    opt.value = 0;
                    $("#product_category_type_id").append(opt);
                    $(result.cattype).each(function(index,item){
                        var opt = document.createElement("OPTION");
                        opt.text = item['CategoryTypeI18n']['name'];
                        opt.value = item['CategoryType']['id'];
                        if(opt.value==result.last_category_type){
                            opt.selected=true;
                        }
                        $("#product_category_type_id").append(opt);
                    })
                    $("#product_category_id").trigger('changed.selected.amui');
                    $("#productcattype .am-close").click();
                    document.getElementById("CattypeCode").value="";
                    document.getElementById("CattypeName").value="";
                }
                if(result.flag==2){
                    alert(result.message);
                }
            }
        });
    }
}

//快速添加品牌
function doinsertbrand(){
    var logoname=document.getElementById("brand_name_"+backend_locale).value;
    var logocode=document.getElementById("BrandCode").value;
    if(logoname==""||logocode==""){
        alert("品牌名称和代码不能为空");
        return;
    }else{
        var sUrl = "/admin/brands/doinsertbrand/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data:$('#catform2').serialize(),
            success: function (result) {
                if(result.flag==1){
                    $("#product_brand_id option").remove();
                    var opt = document.createElement("OPTION");
                    opt.text = "<?php echo $ld['select_brands']; ?>";
                    opt.value = 0;
                    $("#product_brand_id").append(opt);
                    $(result.brand).each(function(index,item){
                        var opt = document.createElement("OPTION");
                        opt.text = item['BrandI18n']['name'];
                        opt.value = item['Brand']['id'];
                        if(opt.value==result.last_brand){
                            opt.selected=true;
                        }
                        $("#product_brand_id").append(opt);
                    })
                    $("#product_brand_id").trigger('changed.selected.amui');
                    
                    $("#brand .am-close").click();
                    document.getElementById("brand_name_"+backend_locale).value="";
                    document.getElementById("BrandCode").value="";
                }
                if(result.flag==2){
                    alert(result.message);
                }
            }
        });
    }
}

function show_upload_div(id){
    document.getElementById("upload_input").value=id;
}

//图片旋转新开窗口
function upload_img(id_str){
    var path=admin_webroot+'image_spaces/uploadproductrotation/'+id_str;
    window.open(path,'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
}

//快速上传文件
function doupload_file(){
    var sUrl = "/admin/upload_files/ajax_add/";//访问的URL地址
    var formData = new FormData($( "#upload_file_form" )[0]);
    $.ajax({
        type: "POST",
        url: sUrl,
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if(result.flag==1){
                var upload_input=$("#upload_input").val();
                $("#ProductDownload"+upload_input).val(result.file_url);
                $(".am-close").click();
            }
            if(result.flag==0){
                alert(result.msg);
            }
        }
    });
}

function changePackageProductOrder(updown,id,next,thisbtn){
    var productid = document.getElementById("productid").value;
    changeHtml(thisbtn);
    var sUrl = "/admin/products/change_packageorder/"+updown+"/"+id+"/"+next+"/"+productid;//访问的URL地址
    $.ajax({
        type: "POST",
        url: sUrl,
        dataType: 'json',
        success: function (result) {
            $("#package_table").html(result.content);
        }
    });
}

function changeOrder(p_id,id){
    var sUrl = "/admin/products/changeorder/"+p_id+"/"+id;//访问的URL地址
    $.ajax({
        type: "POST",
        url: sUrl,
        dataType: 'html',
        success: function (json) {
            $("#ph_show").html(json);
        }
    });
}

/*增加下拉框*/
function addSellercid(obj,k){
    var src = obj.parentNode.parentNode;
    var idx = rowindex(src);
    var tbl = document.getElementById('sellercid_tables_'+k);
    var row = tbl.insertRow(idx + 1);
    var cell = row.insertCell(-1);
    cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addSellercid)(.*)(\[)(\+)/i, "$1removeSellercid$3$4-");
}

/*移除下拉框*/
function removeSellercid(obj,k){
    var row = rowindex(obj.parentNode.parentNode);
    var tbl = document.getElementById('sellercid_tables_'+k);
    tbl.deleteRow(row);
}

function addaddr(obj){
    var src = obj.parentNode.parentNode;
    var idx = rowindex(src);
    var tbl = document.getElementById('addr-tables');
    var row = tbl.insertRow(idx + 1);
    var cell = row.insertCell(-1);
    var img_str = src.cells[0].innerHTML.replace(/(.*)(addaddr)(.*)(\[)(\+)/i, "$1removeaddr$3$4-");
    cell.innerHTML = img_str;
    for(var i=1;i<5;i++){
        row.insertCell(-1).innerHTML=src.cells[i].innerHTML;
    }
}

function removeaddr(obj){
    var row = rowindex(obj.parentNode.parentNode);
    var tbl = document.getElementById('addr-tables');
    tbl.deleteRow(row);
}

//品牌关联货号
function changeProductCode(obj){
    if(obj.id=='product_brand_id'){
        var sUrl = "/admin/products/change_product_code/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {val:obj.value},
            success: function (result) {
                if(result.flag==1){
                    code1 = result.code;
                    document.getElementById('product_brand_id_h').value = result.code;
                }else{
                    document.getElementById('product_brand_id_h').value = '';
                }
            }
        });
    }else if(obj.id=='yb_cid'){
        code2 = obj.value;
        document.getElementById('product_num').value = code1+code2+code3;
    }
}

function changedelivery(obj,k){
    var tab=document.getElementById(k);
    if(obj.value!="0"){
        tab.className += " delivery";
    }else{
        tab.className = "alonetable";
    }
}

function changebuyer(obj,k,kk){
    var tab=document.getElementById(k);
    if(obj.value=="buyer"){
        tab.className = "alonetable";
        if(document.getElementById("postage_id_"+kk).value!="0"){
            tab.className += " delivery";
        }else{
            tab.className = "alonetable";
        }
    }else{
        tab.className = "alonetable seller";
    }
}

function changeseller(obj,k){
    var tab=document.getElementById(k);
    if(obj.value=="seller"){
        tab.className = "alonetable seller";
    }else{
        tab.className = "alonetable";
    }
}

function auto_code(){
    var obj1 = document.getElementById('product_num');
    var val = obj1.value;
    var brand_code=document.getElementById('product_brand_id_h').value;
    var category_id=document.getElementById('product_category_id').value;
    var category_type_id=document.getElementById('product_category_type_id').value;
    var sUrl = "/admin/products/auto_code/";//访问的URL地址
    $.ajax({
        type: "POST",
        url: sUrl,
        dataType: 'json',
        data: {val:val,brand_code:brand_code,category_id : category_id,category_type_id:category_type_id},
        success: function (result) {
            if(result.flag==1){
                document.getElementById('product_num').value = result.code;
            }else{
                alert(result.code);
            }
        }
    });
}

function search_brand(){
    var obj1 = document.getElementById('brand_keyword'),
        obj2 = document.getElementById('product_brand_id');
    var keyword=obj1.value;
    var sUrl = "/admin/brands/search_brand/";//访问的URL地址
    $.ajax({
        type: "POST",
        url: sUrl,
        dataType: 'json',
        data: {val:keyword},
        success: function (result) {
            $("#product_brand_id option").remove();
            var opt = document.createElement("OPTION");
            opt.text = "<?php echo $ld['select_brands']; ?>";
            opt.value = 0;
            $("#product_brand_id").append(opt);
            if(result.flag==1){
                obj2.innerHTML='';
                var _item = document.createElement("option");
                obj2.options.add(_item);
                _item.value = '0';
                _item.innerHTML = "<?php echo $ld['select_brands']?>";
                $.each(result.content,function(k,v){
                    var product_num = document.getElementById("product_num").value;
                    var item = document.createElement("option");
                    obj2.options.add(item);
                    item.value = v['Brand']['id'];
                    item.innerHTML =v['BrandI18n']['name'];
                    if(product_num==""){
                        if(k==0){
                            $("#product_num").val(v['Brand']['code']);
                        }
                        $('#product_num').val(v['Brand']['code']);
                    }
                });
            }else{
                alert(result.content);
            }
            $("#product_brand_id").trigger('changed.selected.amui');
        }
    });
}

//删除商品图片
function delProImg(Id,i,product_id){
    var sUrl = "/admin/products/delProImg/"+product_id;//访问的URL地址
    $.ajax({
        type: "POST",
        url: sUrl,
        dataType: 'json',
        data: {Id:Id},
        success: function (result) {
            if(result.code==1){
                if(product_id==''){
                    $('#show_product_add_img_0'+i).attr('src','/media/default_no_photo.png');
                    $('#product_add_img_0'+i).val('');
                    $('#img_detail_product_add_img_0'+i).val('');
                    $('#img_big_product_add_img_0'+i).val('');
                    $('#td_'+i).html('');
                    <?php   foreach($backend_locales as $k => $v){?>
                    $('#ProductGalleryI18n_'+i+'_<?php echo $k;?>_description').val('');
                    <?php   }?>
                }else{
                    window.location.reload();
                }
            }
            if(result.code==2){
                alert(result.msg);
            }
        }
    });
}

function checkUploadFile(arg,obj){
    var fileSize;
    var bro = getBroswerType();
    if(bro.firefox){
        fileSize = arg.files.item(0).fileSize
    }else if(bro.ie){
    }
    if(lastname(obj))
    {
        if(fileSize > 500*1024){
            arg.value="";
            alert("<?php echo $ld['file_attachment_size_exceeds']?>");
        }
    }
}

function lastname(obj){
    var filepath = document.getElementById(obj).value;
    var re = /(\\+)/
    var filename=filepath.replace(re,"#");
    //对路径字符串进行剪切截取
    var one=filename.split("#");
    //获取数组中最后一个，即文件名
    var two=one[one.length-1];
    //再对文件名进行截取，以取得后缀名
    var three=two.split(".");
    //获取截取的最后一个字符串，即为后缀名
    var last=three[three.length-1];
    //添加需要判断的后缀名类型
    var tp = "<?php echo empty($file_types)? '':$file_types; ?>";
//  var tp ="ico sql";
    //返回符合条件的后缀名在字符串中的位置
    var rs=tp.indexOf(last);
    //如果返回的结果大于或等于0，说明包含允许上传的文件类型
    if(rs>=0){
        return true;
    }else{
        alert("<?php echo $ld['file_format_error']?>");
        document.getElementById(obj).value = "";
        document.getElementById(obj).outerHTML=document.getElementById(obj).outerHTML.replace(/(value=\").+\"/i,"$1\"");
        return false;
    }
}

function getBroswerType(){
    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    var s;
    (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
        (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
            (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
                (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
                    (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
    return Sys
}

//条件标签
function addtags(obj,name){
    var val=$(obj).next().val()
    var text = '<span class="taglist" ><span class="am-icon-minus am-fl" style="padding-top:8px;" onclick="removetags(this)"></span><span class="am-fl"><input type="text" style="margin-bottom: 10px;width: 100px;" name="'+name+'" value="'+val+'" /></span><span class="am-cf"></span></span>';
    obj.insertAdjacentHTML("beforeBegin",text);
}

function removetags(obj){
    $(obj).parent().remove();
}

var tag_remove = function(e){
    var tmpbtn = e;
    alert(tmpbtn.type);
    if(tmpbtn.type == "text"){
        return false;
    }
    if(tmpbtn.className == "dishow"){
        var tmppele = tmpbtn.parentNode;
        if (!tmppele.className.match("input_editing")) {
            tmppele.className +=" input_editing";
            tmppele.nextSibling.className +=" span_editing";
        };
    }else{
        tmpbtn.parentNode.removeChild(tmpbtn);
    }
}

var tag_add_key = function(e){
    if (e.keyCode == 13) {
        tag_add(e);
    };
    return false;
}
var tag_add_blur = function(e){
    tag_add(e);
    return false;
}

var img_remove = function(e){
    var tmpbtnremove = e.currentTarget._node;
    var tmpli = tmpbtnremove.parentNode.parentNode;
    if(confirm("<?php echo $ld['confirm_delete'] ?>")){
        tmpli.parentNode.removeChild(tmpli);
    }
}

//添加套装商品
function add_sel(obj){
    //if($("#show_select_package div").length<5){
        var id=$(obj).attr('id');
        var del_id=id.replace("add_sel_package","del_selected");
        id=id.replace("add_sel_package","");
        $(obj).parent().css("display","none");
        $(obj).parent().parent().find('.show_package_del').css("display","block");
        var name=$(obj).parent().parent().find('.show_package_name').html();
        var select_package="<div style='clear:both;width:425px;height:26px;line-height: 26px;'><span style='float:left;'>"+name+"</span> <span style='float:right;'><input onclick='del_sel(this)' class='del_selected am-btn am-btn-danger am-btn-xs am-radius' type='button' value='<?php echo $ld['delete']?>' id='"+del_id+"'></span></div>";
        if($("#show_select_package").html()==""){
            $("#show_select_package").html(select_package);
        }else{
            $("#show_select_package").append(select_package);
        }
        var selected_package=$("#selected_package").val();
        if(selected_package==""){
            $("#selected_package").val(id+";");
        }else{
            $("#selected_package").val(selected_package+id+";");
        }
//    }else{
//        alert("添加的套装商品不能超过5个");
//    }
}

//切割数组方法
function arr_splice(){
    var sel_package=$("#selected_package").val();
    var package_arr = sel_package.split(";");
    return package_arr;
}

//选择框中的删除
$(".del_sel_package").on("click",function(){
    var id=$(this).attr('id');
    var add_id=id.replace("del_sel_package","add_sel_package");//显示添加按钮
    $("#"+add_id).parent().css("display","block");
    $(this).parent().css("display","none");//隐藏删除按钮
    var sel_id=id.replace("del_sel_package","del_selected");//隐藏显示的已添加商品
    id=id.replace("del_sel_package","");
    var package_arr=arr_splice();
    $.each(package_arr, function(key, val) {
        if(val==id){
            package_arr.splice($.inArray(id,package_arr),1);
            $("#"+sel_id).parent().parent().remove();
        }
    });
    var sel_package = package_arr.join(";");
    $("#selected_package").val(sel_package);
});

//选择后的删除
function del_sel(obj){
    var id=$(obj).attr('id');
    var add_id=id.replace("del_selected","add_sel_package");//显示添加按钮
    $("#"+add_id).parent().css("display","block");
    var del_id=id.replace("del_selected","del_sel_package");//隐藏删除按钮
    $("#"+del_id).parent().css("display","none");
    var sel_id=id.replace("del_selected","");//删除隐藏的数据
    var package_arr=arr_splice();
    $.each(package_arr, function(key, val) {
        if(val==sel_id){
            package_arr.splice($.inArray(sel_id,package_arr),1);
            $("#"+id).parent().parent().remove();
        }
    });
    var sel_package = package_arr.join(";");
    $("#selected_package").val(sel_package);
}

//提交套装商品
function submit_package(){
    $.ajax({
        cache: true,
        type: "POST",
        url:"/admin/products/add_package_product",
        data:$('#packageform').serialize(),// 你的formid
        async: false,
        success: function(data) {
            var result= JSON.parse(data);
            var tablelist=document.getElementById("package_table");
            tablelist.innerHTML=result.content;
            $(".am-close").click();
        }
    });
}

//搜索框回车控制
$(document).keypress(function(event){
	var TagName=event.target.tagName;
	if(TagName=='TEXTAREA'){return;}
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
		return false;
	}
});

//搜索套装商品
function get_page_pro(){
    var category_id=$("#category_package_id").val();
    var productid=$("#productid").val();
    var product_keyword=$("#product_package_keyword").val();
    $.ajax({ url: "/admin/products/search_package_products",
        type:"POST",
        data: { 'category_id': category_id, 'product_keyword': product_keyword,'productid': productid},
        success: function(data){
            var result=eval('result='+data);
            if(result.flag==1){
                if(result.content){
                    var package_html="";
                    var package_arr=arr_splice();
                    for(i=0;i<result.content.length;i++){
                        if($.inArray(result.content[i]['Product'].id,package_arr)==-1){
                            package_html+="<div class='show_search_package'>";
                            package_html+="<img class='package_img' src='"+result.content[i]['Product'].img_thumb+"'>";
                            package_html+="<div class='show_package_name'>"+result.content[i]['ProductI18n'].name+"</div>";
                            package_html+="<div class='show_package_code'>"+result.content[i]['Product'].code+"</div>";
                            package_html+="<div class='show_package_price'>"+result.content[i]['Product'].shop_price+"</div>";
                            package_html+="<div class='show_package_qty'>"+result.content[i]['Product'].quantity+"</div>";
                            package_html+="<div class='show_package_add'><input class='add_sel_package' onclick='add_sel(this)' type='button' value='<?php echo $ld['add']?>' id='add_sel_package"+result.content[i]['Product'].id+"' /></div>";
                            package_html+="<div class='show_package_del'><span class='addeds_package'><?php echo $ld['added']?></span><img src='/admin/skins/default/img/delete.png' class='del_sel_package' type='button' title='<?php echo $ld['delete']?>' id='del_sel_package"+result.content[i]['Product'].id+"' /></div>";
                            package_html+="</div>";
                        }else{
                            package_html+="<div class='show_search_package'>";
                            package_html+="<img class='package_img' src='"+result.content[i]['Product'].img_thumb+"'>";
                            package_html+="<div class='show_package_name'>"+result.content[i]['ProductI18n'].name+"</div>";
                            package_html+="<div class='show_package_code'>"+result.content[i]['Product'].code+"</div>";
                            package_html+="<div class='show_package_price'>"+result.content[i]['Product'].shop_price+"</div>";
                            package_html+="<div class='show_package_qty'>"+result.content[i]['Product'].quantity+"</div>";
                            package_html+="<div class='show_package_add' style='display:none;'><input onclick='add_sel(this)' class='add_sel_package' type='button' value='<?php echo $ld['add']?>' id='add_sel_package"+result.content[i]['Product'].id+"' /></div>";
                            package_html+="<div class='show_package_del' style='display:block;'><span class='addeds_package'><?php echo $ld['added']?></span><img src='/admin/skins/default/img/delete.png' class='del_sel_package' type='button' title='<?php echo $ld['delete']?>' id='del_sel_package"+result.content[i]['Product'].id+"' /></div>";
                            package_html+="</div>";
                            var select_package="<div style='clear:both;width:425px;height:26px;line-height: 26px;'><span style='float:left;'>"+result.content[i]['ProductI18n'].name+"</span> <span style='float:right;'><input class='del_selected' type='button' value='<?php echo $ld['delete']?>' id='del_selected"+result.content[i]['Product'].id+"'></span></div>";
                            if($("#show_select_package").html()=="" && $.inArray(result.content[i]['Product'].id,package_arr)){
                                $("#show_select_package").html(select_package);
                            }
                        }
                    }
                    $("#select_package").html(package_html);
                    $("#select_package").css("display","block");
                }
            }else{
                alert("该分类没有商品");
                $("#select_package").html("");
                $("#select_package").css("display","none");
            }
        }});
}

</script>

<!--滚动条css-->
<style>
    #package_table .tablelist tr td a{margin:0;}
    #package_table .tablelist tr td a:hover{text-decoration:none;}
    #package_table .tablelist tr .thsort{text-align:center;width:50px;}
    #taozhuang{width:80px;padding:0px 8px;}
    #show_package{float:left;background:#E9F8D9;margin:5px 0 0 10px;width:580px;min-height:55px;height:auto;}
    .show_package_del{display:none;float:left;padding-top:10px;height:35px;width:80px;margin-left:30px;}
    .show_package_add{height:35px;width:80px;margin-left:30px;float:left}
    .show_package_name,.show_package_code{width:100px;margin-left:10px;float:left}
    .show_package_name{width:160px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;height:40px;}
    .show_package_qty,.show_package_price{width:50px;margin-left:10px;float:left;text-align: right;}
    .package_img{vertical-align: middle;display: inline;margin-left:5px;float:left;width:45px;height:45px;}
    .show_search_package{line-height:45px;height:45px;font-size: 14px;margin: 5px 0 0;padding: 5px 0 0;overflow: hidden;word-wrap: break-word;text-align:left;border-top: 1px solid #CCCCCC;}
    .show_search_package .add_sel_package{
        background-color:#f87620;
        border: medium none;
        color: white;
        padding-top:5px;
        padding-bottom:2px;
    }
    .show_search_package .addeds_package{position: relative;top:-10px;color:gray;}
    .show_search_package .del_sel_package{position: relative;top:-7px;left:3px;}
    #package_table table td a.down{color: #093;}
    #scroll-pane,.scroll-pane{position:relative}
    .scroll-content {position:absolute;top:0;left:0}
    .slider-wrap{position:absolute;right:0;top:0;background-color:lightgrey;width:20px;border-left:1px solid gray;}
    .slider-vertical{position:relative;height:100%}
    .ui-slider-handle{width:20px;height:10px;margin:0 auto;background-color:darkgray;display:block;position:absolute}
    .ui-slider-handle img{border:none}
    .scrollbar-top{position:absolute;top:0;}
    .scrollbar-bottom{position:absolute;bottom:0;}
    .scrollbar-grip{position:absolute;top:50%;margin-top:-6px;}
    .ui-slider-range{position:absolute;width:100%;background-color:lightgrey}
    .container{height:337px;width:506px;float:left;margin-left:50px;display:inline;margin-bottom:40px}
    #select_package,#select_package_test, .scroll-pane {background-color: #FFFFFF;
        /*border: 1px solid #808080;*/
        display: inline;float: left;height: 286px;margin-bottom:5px;margin-left: 10px;overflow: auto;position: relative;width: 580px;display:none;
    }
    .scroll-content-item {background-color: #FCFCFC;border: 1px solid #808080;color: #003366;display: inline;float: left;font-size: 3em;height: 100px;line-height: 96px;margin: 10px;text-align: center;width: 100px;}
    #productSku_list .listtable{width:100%;}
    #productSku_list .listtable td{border: 1px solid #CDCDCD;text-align:center;}
</style>
<!--[if IE]>
<style>
    .show_package_del{display:none;float:left;padding-top:10px;height:35px;width:90px;margin-left:10px;}
    .show_package_del input[type='button']{padding:3px 0px;margin:0;}
    .show_package_add{padding-top:10px;height:35px;width:90px;margin-left:10px;float:left}
    .show_search_package .add_sel_package{padding-top:5px;}
</style>
<![endif]-->
<script type="text/javascript">
$(function(){
    handlePromote($("#promotion_status").prop("checked"));
})

    var sku_attr_code=[];//记录商品属性code
    //获取销售属性
    function getSkuType(Id,obj){
        var attr_id=obj.value;
        if(attr_id!=""){
            $.ajax({ url: "/admin/products/getskutype",
                type:"POST",
                data: { 'pro_id': Id, 'pro_type': attr_id},
                success: function(data){
                    sku_attr_code=[];
                    var result=eval("("+data+")");
                    $("#productSku_list").html(result.sku_html);
                    if(result.sku_attr_code!=null){
                        for(var i=0;i<result.sku_attr_code.length;i++){
                            sku_attr_code.push(result.sku_attr_code[i]);
                        }
                    }
                }
            });
        }else{
            $("#productSku_list").html("");
        }
    }
    //删除销售属性
    function remove_sku_pro(pro_code){
        $("#skutype_info_list tbody").find("#sku_code_"+pro_code).remove();
        $("#sku_search_pro .sku_pro_code_value").each(function(){
            if($(this).val()==pro_code){
                var TR_obj=$(this).parent().parent();
                TR_obj.find("input[type=checkbox]").attr("checked",false);
            }
        });
        check_sku_data();
    }

    function check_sku_data(ck_flag){
        if(ck_flag=='undefined'||ck_flag==null){
            ck_flag=false;
        }
        var add_sku_flag=0;
        var TR_obj=null;
        var TR_obj2=null;
        $("#sku_search_pro tbody tr").each(function(index,value){
            add_sku_flag=0;
            TR_obj=$(this);
            var pro_code=TR_obj.find(".sku_pro_code_value").val();
            TR_obj.find("input[type=checkbox]").attr("disabled",false);
            $("#skutype_info_list tbody tr").each(function(index2,value2){
                TR_obj2=$(this);
                $.each(sku_attr_code, function(i, n){
                    var attr_value=TR_obj.find(".pro_data_attr_"+n).val();
                    var log_attr_value=TR_obj2.find(".data_"+n).val();
                    var log_attr_code=TR_obj2.attr("id");
                    if(log_attr_value==attr_value){
                        add_sku_flag++;
                    }else if(log_attr_code=="sku_code_"+pro_code){
                        if(ck_flag){
                            add_sku_flag++;
                        }
                    }
                });
                if(add_sku_flag>(sku_attr_code.length-1)){
                    if(TR_obj!=null){
                        TR_obj.find("input[type=checkbox]").attr("disabled",true);
                    }
                }
                add_sku_flag=0;
                TR_obj2=null;
            });
            TR_obj=null;
        });
    }

    $(".am-panel-bd").on("click","#productSku_list #productSku_list_table #sku_search_pro .sku_pro_ck",function(){
        var TR_obj=$(this).parent().parent();
        var ck_value=$(this).val();
        var sku_pro_code=TR_obj.find(".sku_pro_code_value").val();
        var sku_pro_name=TR_obj.find(".sku_pro_name_value").val();
        var sku_pro_quantity=TR_obj.find(".sku_pro_quantity_value").val();
        var sku_pro_shop_price=TR_obj.find(".sku_pro_shop_price_value").val();
        if($(this).is(':checked')){
            var sku_data_name=TR_obj.find(".sku_data_name").val();
            attr_html="<tr id='sku_code_"+sku_pro_code+"'>";
            attr_html+="<td>"+sku_pro_name+"</td><td>"+sku_pro_code+"</td><td>"+sku_pro_quantity+"<input type='hidden' name='data[SkuProduct][quantity][]' value='"+sku_pro_quantity+"' /></td><td>"+sku_pro_shop_price+"</td>";
            $.each(sku_attr_code, function(i, n){
                var attr_id=TR_obj.find("#"+ck_value+"_"+n+"_id").val();
                var attr_val=TR_obj.find("#"+ck_value+"_"+n+"_value").val();
                attr_html+="<td>"+attr_val+"<input type='hidden'class='data_"+n+"' value='"+attr_val+"'></td>";
            });
            attr_html+="<td><input type='hidden' value='"+sku_pro_code+"' name='data[SkuProduct][sku_product_code][]'  /><input type='text' value='"+sku_pro_shop_price+"' name='data[SkuProduct][price][]'  /></td>";
            attr_html+="<td><a href='javascript:void(0);' class='remove_sku_attr am-btn am-btn-danger am-btn-xs am-radius' onclick='remove_sku_pro(\""+sku_pro_code+"\")'><?php echo $ld['remove']; ?></a></td>";
            attr_html+="</tr>";
            $("#skutype_info_list tbody").append(attr_html);
        }else{
            $("#skutype_info_list tbody").find("#sku_code_"+sku_pro_code).remove();
        }
        check_sku_data(true);
    });
    <?php
        if(!empty($product_type_list) && strip_tags($products_attr_html)!=""&&isset($product_info['Product']['id'])&&$is_sku==false&&$option_type_id==0){//当前商品为普通商品且不为销售属性子商品
    ?>
    $("#product_type options[value=<?php echo (empty($product_info['Product']['product_type_id'])?'0':$product_info['Product']['product_type_id']);?>]").attr("selected","selected");
    getAttrList("<?php echo (empty($product_info['Product']['id'])?0:$product_info['Product']['id']);?>");
    <?php } ?>

    <?php if($is_sku==false&&$option_type_id==2){ ?>
    getSkuType("<?php echo (empty($product_info['Product']['id'])?0:$product_info['Product']['id']);?>",document.getElementById("product_sku_type"));
    <?php } ?>
</script>
