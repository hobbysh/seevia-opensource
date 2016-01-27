<style type="text/css">

    
    .am-form-label{font-weight:bold;text-align:center;margin-top:5px;margin-left:20px; }
</style>
<div class="listsearch">
    <?php echo $form->create('',array('action'=>'/',"type"=>"get",'name'=>"SearchForm",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    
    <ul class="am-avg-lg-3  am-avg-md-2 am-avg-sm-1" style="margin:10px 0 0 0;">
    	 <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-4  am-u-sm-4 am-form-label"><?php echo $ld['type'] ?></label>
            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7   ">
                <select name="type" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value=""><?php echo $ld['all_data']?></option>
                    <option value="P" <?php if("P"==@$type){ echo "selected";}?> ><?php echo $ld['product']?></option>
                    <option value="O" <?php if("O"==@$type){ echo "selected";}?> ><?php echo $ld['order']?></option>
                </select>
            </div>
        </li><!----------1------------->
         <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3  am-u-md-4   am-u-sm-4 am-form-label"><?php echo $ld['meessage_title'];?></label>
            <div class="am-u-lg-7am-u-md-7 am-u-sm-7   ">
                <input type="text" name="title" value="<?php echo @$titles?>" />
            </div>
        </li><!-----------2------------>
         <li style="margin:0 0 10px 0">
        	 <label class="am-u-lg-3  am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['message_time'];?></label>
            <div class="am-u-lg-3  am-u-md-3 am-u-sm-3 " style="padding:0 0.5rem;">
                <input style=" min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_time" value="<?php echo @$start_time;?>" />
            </div>
            <em class=" am-u-lg-1  am-u-md-1 am-u-sm-1  am-text-center" style="padding-top:5px;">-</em>
            <div class="  am-u-lg-3  am-u-md-3  am-u-sm-3 am-u-end" style="padding:0 0.5rem;">
                <input style=" height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_time" value="<?php echo @$end_time;?>" />
            </div>		
        </li>  <!------------3---------->
         <li style="margin:0 0 10px 0">
         		
             <label class="am-u-lg-3 am-u-md-4  am-u-sm-4  am-form-label"><?php echo $ld['reply'].$ld['status'];?></label>
            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7   am-u-end" >
                <select name="reply_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}" >
                    <option value=""><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if("0"==@$reply_status){ echo "selected";}?>><?php echo $ld['unreplied']?></option>
                    <option value="1" <?php if("1"==@$reply_status){ echo "selected";}?>><?php echo $ld['replied']?></option>
                </select>
            </div>
        </li><!-----------4------------>
              <li style="margin:0 0 10px 0px" > 
	            <label class="am-u-lg-3  am-u-md-4   am-u-sm-4 am-form-label"> </label>
	            <div class="am-u-lg-7am-u-md-7 am-u-sm-7 ">
	                 	  <input type="submit" class=" am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" onclick="search_user()"  />
	          	 <input type="hidden" name="search" value="search"/>
	            </div>
	            
          	  </li>
         
    </ul>
    <?php echo $form->end();?>
</div>
<p class="am-u-md-12 am-btn-group-xs">
    <?php if($svshow->operator_privilege("block_words_view")){echo $html->link($ld['shielding_keyword'],"/block_words/",array('target'=>'_blank',"class"=>"am-btn  am-radius am-btn-default am-btn-sm am-fr"));} ?>
</p>
<?php echo $form->create('',array('action'=>'/','name'=>'UserForm','type'=>'get','onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th  style="width:100px;"  class="thwrap am-hide-sm-down"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b> <?php echo $ld['member_name']?></b></label>
            </th>
             <th ><?php echo $ld['meessage_title']?></th>
             <th  style="width:100px;" ><?php echo $ld['content']?></th>
             <th  style="width:100px;" class="thwrap am-hide-sm-down"><?php echo $ld['type']?></th>
             <th class="thwrap am-hide-md-down"><?php echo $ld['message_time']?></th>
             <th style="width:130px;" ><?php echo $ld['reply'].$ld['status']?></th>
             <th ><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($UserMessage_list) && sizeof($UserMessage_list)>0){?>
            <?php foreach($UserMessage_list as $k=>$v){ ?>
                <tr>
                    <td class="thwrap  am-hide-sm-down"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['UserMessage']['id'] ?>" /></span><span><?php echo $v['UserMessage']['user_name'] ?></span> </label>
                    </td>
                    <td > <?php echo $v['UserMessage']['msg_title'] ?> </td>
                    <td > <?php echo $v['UserMessage']['msg_content'] ?> </td>
                    <td class="thwrap am-hide-sm-down"><?php echo $v['UserMessage']['type']=='P'?$ld['product_questions']:''; ?></td>
                    <td class="thwrap am-hide-md-down"><?php echo $v['UserMessage']['created'] ?></td>
                    <td ><?php if(isset($replycount_list[$v['UserMessage']['id']])){ echo $ld['replied'];}else{echo $ld['unreplied'];}?></td>
                    <td style="min-width:200px;"><?php
                        if($svshow->operator_privilege("messages_edit")){
                            echo $html->link($ld['reply'],"view/{$v['UserMessage']['id']}",array("class"=>"mt am-btns am-btn am-btn-default am-btn-xs  ")) ;
                        }
                        if($svshow->operator_privilege("messages_remove")){?>
                           <a class=" mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'messages/remove/<?php echo $v['UserMessage']['id'] ?>');"> <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a>
                       <?php } ?></td>
                </tr>
            <?php }}else{?>
            <tr>
                <td colspan="8" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
        <?php if(isset($UserMessage_list) && sizeof($UserMessage_list)>0){?>
            <div id="btnouterlist" class="btnouterlist">
                <?php if($svshow->operator_privilege("messages_remove")){?>
                <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-hide-sm-down" style="margin-left:1px;">
                    <label class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
                    <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" onclick="if(confirm('<?php echo $ld['confirm_delete']; ?>')){batch_action();}" value="<?php echo $ld['batch_delete']?>" name="act_type" />
                </div>
                <?php } ?>
                <div class="am-u-lg-8 am-u-md-7 am-u-sm-12">
        			<?php echo $this->element('pagers');?>
        		</div>
                <div class="am-cf"></div>
            </div>
    <?php } ?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function batch_action()
    {
        document.UserForm.action=admin_webroot+"messages/batch";
        document.UserForm.onsubmit= "";
        document.UserForm.submit();
    }
    
    function search_user()
    {
        document.SearchForm.onsubmit= "";
        document.SearchForm.action=admin_webroot+"messages/";
        document.SearchForm.submit();
    }
</script>