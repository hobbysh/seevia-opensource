<?php 
	echo $form->create('block_words',array('action'=>'/view/'.$id,'name'=>"SeearchForm",'id'=>"SearchForm","type"=>"post",'onsubmit'=>'return formsubmit();'));
?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
        	<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
        		<input type="hidden" name="data[BlockWord][id]" value="<?php echo $id; ?>" />
            	<table class="am-table">
            		<tr>
						<td style="padding-top:13px;"><?php echo $ld['type'] ?></td>
						<td>
							<select data-am-selected="{noSelectedText:''}" name="data[BlockWord][type]" id="word_type">
								<option value=""><?php echo $ld['please_select'] ?></option>
								<option value="0" <?php echo isset($wordinfo)&&$wordinfo['BlockWord']['type']=="0"?"selected":""; ?>><?php echo $ld['filter'] ?></option>
								<option value="1" <?php echo isset($wordinfo)&&$wordinfo['BlockWord']['type']=="1"?"selected":""; ?>><?php echo $ld['replace'] ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="padding-top:15px;"><?php echo $ld['keyword'] ?></td>
						<td>
							<input style="width:200px;" type="text" name="data[BlockWord][word]" id="word" value="<?php echo isset($wordinfo)?$wordinfo['BlockWord']['word']:""; ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
						</td>
					</tr>
            	</table>
            </div>
        </div>
     </div>
</div>
<?php
	echo $form->end();
?>
<script type="text/javascript">
function formsubmit()
{
	var type=document.getElementById("word_type").value;
	var word=document.getElementById("word").value;
	if(type==""){
		alert('请选择类型');
		return false;
	}
	if(word==""){
		alert('关键字不能为空');
		return false;
	}
	return true;
}
</script>