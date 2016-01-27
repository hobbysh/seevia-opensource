<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#type"><?php echo $ld['product'].$ld['type'];?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="type" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['product'].$ld['type'];?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" >
               <div  class="am-form-group">
	                       <label class="am-view-label am-u-lg-2"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].''.$ld['type']:$ld['please_select'].$ld['type'];?>:</label> 
                                       <div class="am-u-lg-5 am-u-end"><div>
		                             <select  id="productType">
		                                <option value=""><?php echo $ld['please_select'];?></option>
		                                <option value="p0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['ordinary'].' '.$ld['product']:$ld['ordinary'].$ld['product'];?></option>
		                                <option value="p1"><?php echo $ld['package_product'];?></option>
		                                <option value="p2"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['sales_attribute'].' '.$ld['product']:$ld['sales_attribute'].$ld['product'];?></option>
		                            </select>
                                          </div></div>
		                   </div>
               <div class="am-cf"></div>
                <div class="btnouter" style="margin-top:10px;" >
                    <input type="submit" class="am-btn am-btn-success" value="<?php echo $ld['confirm']?>" onclick="toAddProduct()" /> <input type="reset" class="am-btn am-btn-success" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toAddProduct(){
        var productType=document.getElementById("productType").value;
        if(productType!=""){
            window.location.href="/admin/products/view/0?productType="+productType;
        }else{
            alert("<?php echo $ld['please_select'].$ld['type'] ?>");
            return false;
        }
    }
</script>