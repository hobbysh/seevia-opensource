<style>
  
     .min_w{min-width:88px;}
</style>

<p class="am-u-md-12 am-btn-group-xs" style="margin-top:10px;">
    <?php if($svshow->operator_privilege("payments_edit")){?>
    
	    <!--echo $html->link($ld['add'],'/payments/view/0',array("class"=>"am-btn am-btn-warning am-radius am-btn-sm am-fr"),false,false);-->
	       <a class="am-btn  am-btn-sm am-btn-warning am-radius am-fr" href="<?php echo $html->url('/payments/view/0')   ?>">
	        		<span class="am-icon-plus"></span>
	           		<?php  echo  $ld['add'] ?>
	           </a>
	    	<?php }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr >
            <th class="min_w"><?php echo $ld['payment_name']?></th>
            <th class="am-hide-sm-only"><?php echo $ld['code']?></th>
            <th class="min_w"><?php echo $ld['payment_description']?></th>
            <th  class="am-hide-sm-only"><?php echo $ld['fee']?></th>
            <th class="am-hide-sm-only" ><?php echo $ld['sort']?></th>
            <th  ><?php echo $ld['valid']?></th>
            <th ><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($payment_tree) && sizeof($payment_tree)>0){foreach($payment_tree as $k=>$payment){?>
            <tr class="am-panel am-panel-default am-panel-body" id="payment_<?php echo $payment['Payment']['id']; ?>">
                <td class="min_w"><span data-am-collapse="{target: '.payment_<?php echo $payment['Payment']['id']?>'}" class="<?php echo (isset($payment['SubMenu']) && !empty($payment['SubMenu']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;<?php echo $payment['PaymentI18n']['name']?></td>
                <td class="am-hide-sm-only"><?php echo $payment['Payment']['code']?></td>
                <td class="min_w"><div class="ellipsis"><?php echo $payment['PaymentI18n']['description']?></div></td>
                <td class="am-hide-sm-only"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_fee/', <?php echo $payment['Payment']['id']?>)"><?php echo $payment['Payment']['fee']?></span></td>
                <td class="am-hide-sm-only"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_orderby/', <?php echo $payment['Payment']['id']?>)"><?php echo $payment['Payment']['orderby']?></span></td>
                <td><?php if($payment['Payment']['status']) echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$payment["Payment"]["id"].')></div>';else echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$payment["Payment"]["id"].')></div>';?></td>
                <td style="min-width:200px;"><?php
                    if($svshow->operator_privilege("payments_edit")){?>
                        
                           <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit  am-action" href="<?php echo $html->url('/payments/view/'.$payment['Payment']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                      </a>
                    <?php }
                    if($payment['Payment']['status']=='0'){
                        echo $html->link($ld['use'],'/payments/install/'.$payment['Payment']['id'],array("class"=>" am-seevia-btn mt am-btn am-btn-default  am-btn-xs am-radius"),false,false);
                    }else{
                        echo $html->link($ld['disable'],'/payments/uninstall/'.$payment['Payment']['id'],array("class"=>" am-seevia-btn mt am-btn am-btn-default am-btn-xs am-radius"),false,false);
                    }
                    ?></td>
            </tr>
            <?php if(isset($payment['SubMenu']) && !empty($payment['SubMenu'])>0){foreach($payment['SubMenu'] as $kk=>$vv){?>
                <tr class="am-panel-collapse am-collapse am-panel-child payment_<?php echo $payment['Payment']['id']?>" title="<?php echo $payment['Payment']['id']?>">
                    <td>&nbsp;&nbsp;&nbsp;<?php echo $vv['PaymentI18n']['name']?></td>
                    <td class="am-hide-sm-only"><?php echo $vv['Payment']['code']; ?></td>
                    <td><div class="ellipsis"><?php echo $vv['PaymentI18n']['description']?></div></td>
                    <td class="am-hide-sm-only"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_fee/', <?php echo $vv['Payment']['id']?>)"><?php echo $vv['Payment']['fee']?></span></td>
                    <td class="am-hide-sm-only"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_orderby/', <?php echo $vv['Payment']['id']?>)"><?php echo $vv['Payment']['orderby']?></span></td>
                    <td><?php if($vv['Payment']['status']) echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$vv["Payment"]["id"].')></div>';else echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$vv["Payment"]["id"].')></div>';?></td>
                    <td><?php if($svshow->operator_privilege("payments_edit")){?>
                       
                             <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit  am-action" href="<?php echo $html->url('/payments/view/'.$vv['Payment']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                      </a>
                       <?php  }
                        if($payment['Payment']['status']=='0'){
                           echo $html->link($ld['use'],'/payments/install/'.$vv['Payment']['id'],array("class"=>" am-seevia-btn mt am-btn am-btn-default am-btn-xs am-radius "),false,false);
                        }else{
                            echo $html->link($ld['disable'],'/payments/uninstall/'.$vv['Payment']['id'],array("class"=>"am-seevia-btn mt am-btn am-btn-default am-btn-xs am-radius"),false,false);
                        }
                        ?></td>
                </tr>
            <?php }} ?>
        <?php }}?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
$(function(){
	var $collapse =  $('.am-panel-child');
	$collapse.on('opened.collapse.amui', function(){
        var parentbody_id="payment_"+$(this).prop("title");
		var parentbody=$("#"+parentbody_id);
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus")
	});
		
	$collapse.on('closed.collapse.amui', function() {
		var parentbody_id="payment_"+$(this).prop("title");
		var parentbody=$("#"+parentbody_id);
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
})
</script>