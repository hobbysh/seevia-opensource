<?php 
/*****************************************************************************
 * Seevia 资源库管理
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style>
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
</style>
<div class="content">
<!--Main Start-->
	<div class="listsearch ">
		<?php echo $form->create('InformationResource',array('action'=>'/','name'=>"SeearchForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
			<div class="am-form-group">
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label " style="margin-right:0px;"><?php echo $ld['keyword'];?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<input type="text" class="am-input-sm" name="keywords"  id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" style="font-weight:normal ;"/>
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm"><?php echo $ld['search'];?></button>
				</div>
			</div>
		<?php echo $form->end()?>
	</div>
	
	<?php if($svshow->operator_privilege("resources_add")){?>
		<div class="am-text-right" style="margin-bottom:10px;">
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('view/'); ?>" >
			<span class="am-icon-plus"></span><?php echo $ld['add'] ?></a>
		</div>
	<?php }?>
</div>
	<?php echo $form->create('Resources',array('action'=>'','name'=>"ResourceForm","type"=>"get","onsubmit"=>"return false"));?>
		
	<div class="am-g">
		<div class="am-panel-group am-panel-tree" id="accordion">
			<div class="am-panel am-panel-default am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">资源名称</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">资源代码</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">资源值</div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['status'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort'];?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-center"><?php echo $ld['operate'];?></div>
						<div style="clear:both;"></div>
				  	</div>
				</div>
			</div>
		
			<?php if(isset($resource) && sizeof($resource)>0){?><?php foreach($resource as $k => $v){ ?>
				<div>
					<div class="am-panel am-panel-default am-panel-body">
						<div class="am-panel-bd">
							<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
								<span data-am-collapse="{parent: '#accordion', target: '#Resource_<?php echo $v['InformationResource']['id']?>'}" class="<?php echo (isset($v['SubMenu']) && !empty($v['SubMenu']))?"am-icon-plus":"am-icon-minus";?>">&nbsp;
								<?php echo $v['InformationResourceI18n']['name'];?></span>&nbsp;
							</div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $v['InformationResource']['code']?>&nbsp;</div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $v['InformationResource']['information_value']?>&nbsp;</div>					
							<div class="am-u-lg-1 am-show-lg-only">
								<span class="<?php echo (!empty($v['InformationResource']['status'])&&$v['InformationResource']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;
							</div>
							<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['InformationResource']['orderby']?>&nbsp;</div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-right" style="margin-top:5px;">
								<a class="am-btn am-btn-success am-btn-sm am-radius" href="<?php echo $html->url('/information_resources/view/'.$v['InformationResource']['id']); ?>" >
									<?php echo $ld['edit']; ?>
								</a>&nbsp;
								<a class="am-btn am-btn-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>information_resources/remove/<?php echo $v['InformationResource']['id']; ?>')"><?php echo $ld['delete']; ?></a>
							</div>
							<div style="clear:both;"></div>
						</div>
						<?php if(isset($v['SubMenu']) && !empty($v['SubMenu'])){?>
						    <div class="am-panel-collapse am-collapse am-panel-child" id="Resource_<?php echo $v['InformationResource']['id']?>">
						    	<?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
									<div class="am-panel-bd am-panel-childbd">
										<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">&nbsp;&nbsp;&nbsp;
											<?php echo $html->link($vv['InformationResourceI18n']['name'],"view/{$vv['InformationResource']['id']}",array("style"=>"margin-left:20px;"),false,false);?>
										</div>	
										<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $vv['InformationResource']['code']?>&nbsp;</div>
										<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $vv['InformationResource']['information_value']?>&nbsp;</div>
										<div class="am-u-lg-1 am-show-lg-only">
											<span class="<?php echo(!empty($vv['InformationResource']['status'])&&$vv['InformationResource']['status'])?'am-icon-check am-yes':'am-icon-close am-no';?>"></span>&nbsp;
										</div>
										<div class="am-u-lg-1 am-show-lg-only"><?php echo $vv['InformationResource']['orderby']?>&nbsp;</div>
										<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-right">
											<a class="am-btn am-btn-success am-btn-sm am-radius" href="<?php echo $html->url('/information_resources/view/'.$vv['InformationResource']['id']); ?>">
												<?php echo $ld['edit']; ?>
											</a>&nbsp;
											<a class="am-btn am-btn-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>information_resources/remove/<?php echo $vv['InformationResource']['id']; ?>')">
												<?php echo $ld['delete']; ?>
											</a>&nbsp;
										</div>
										<div style="clear:both;"></div>
									</div>
								<?php }?>
							</div>
						<?php }?>
					</div>
				</div>
			<?php }}else{?>
				<div style="text-align:center;margin:50px;"><?php echo $ld['no_page_data']?></div>
			<?php }?>
		</div>
		<div id="btnouterlist" class="btnouterlist"><?php echo $this->element('pagers')?></div>
	</div>
<?php echo $form->end();?>

<!--Main Start End-->

<script type="text/javascript">
$(function(){
	var $collapse =  $('.am-panel-child');
	$collapse.on('opened.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus")
	});
		
	$collapse.on('closed.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
})

function list_delete_submit1(sUrl){
	YUI().use("io",function(Y) {
		var request = Y.io(sUrl, {method: "POST"});//开始请求
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
			}catch (e){
				alert(j_object_transform_failed);
				alert(o.responseText);
			}
			if(result.flag==1){
				window.location.reload();
			}
			if(result.flag==2){
				alert("删除失败，该资源还有子资源");
			}
		}
		var handleFailure = function(ioId, o){

		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}

</script>






