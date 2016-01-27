<style type="text/css">
    .ellipsis{text-transform:capitalize;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:200px;}
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align:text-top;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
.am-form-label{font-weight:bold; left:16px;}
.am-panel-title div{font-weight:bold;}
td img{width:40px;}
tr>td{max-width:100px; "}
tr>td>img{width:150px;}
</style>
<div class="listsearch">
    <?php echo $form->create('',array('action'=>'/','name'=>'UserForm','type'=>'get','onsubmit'=>"return false",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0"><!--1-->
            <label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label "><?php echo $ld['type']?></label>
            <div class="am-u-sm-7 am-u-md-7 zm-u-lg-7">
                <select name="ctype" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}" >
                    <option value=""><?php echo $ld['all_data']?>...</option>
                    <option value="A" <?php if(@$ctype=="A"){echo "selected";}?> ><?php echo $ld['article']?></option>
                    <option value="P" <?php if(@$ctype=="P"){echo "selected";}?> ><?php echo $ld['product']?></option>
                    <option value="T" <?php if(@$ctype=="T"){echo "selected";}?> ><?php echo $ld['topics']?></option>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0"><!--2-->
                 <label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label"><?php echo $ld['status']?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-7 ">
                <select name="cstatus" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value=""><?php echo $ld['all_data']?>...</option>
                    <option value="0" <?php if(@$cstatus=="0"){echo "selected";}?> ><?php echo $ld['no']?></option>
                    <option value="1" <?php if(@$cstatus=="1"){echo "selected";}?> ><?php echo $ld['yes']?></option>
                </select>
               </div> 
        </li>
        <li style="margin:0 0 10px 0"><!--3-->
        	<label class="am-u-sm-3  am-u-md-3 am-u-lg-3 am-form-label"><?php echo $ld['comment_time']?></label>
            <div class="am-u-sm-3 am-u-md-3 am-u-lg-3" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_time" value="<?php echo @$start_time;?>" />
            </div>
            <em class=" am-u-sm-1 am-u-md-1 am-u-lg-1 am-text-center" style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3   am-u-md-3 am-u-lg-3 am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_time" value="<?php echo @$end_time;?>" />
            </div>		
        </li>
   
        <li style="margin:0 0 10px 0"><!--4-->
         
               <label class="am-u-sm-3 am-u-md-3  am-u-lg-3 am-form-label"><?php echo $ld['comment_content'];?></label>
            <div class="am-u-sm-7  am-u-md-7 am-u-lg-7">
                <input type="text"  class="name" name="content" value="<?php echo @$content?>"/>
            </div>
              </li> 
              		<!--5-->
             <li>
             	 <label class="am-u-sm-3 am-u-md-3  am-u-lg-3 am-form-label"> </label>
            <div class="am-u-sm-7  am-u-md-7 am-u-lg-7">
                 <input  class="  am-btn am-btn-success am-radius  am-btn-sm "type="submit" value="<?php echo $ld['search']?>" onclick="search_user()" /> 
            </div>
	            
             </li> 		
        </ul>
     <?php echo $form->end();?>	
</div><br/>
<div>
<p class="am-u-md-12  am-btn-group-xs">
    <?php if($svshow->operator_privilege("scores_log_view")){ echo $html->link($ld['score_log'],"/scores/scorelog",array("class"=>"am-btn am-radius am-btn-sm am-btn-default am-fr"));}?>
</p>
	<div>
<?php echo $form->create('',array('action'=>'',"name"=>"ComForm",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap "style="overflow: hidden; white-space: nowrap;  text-overflow: ellipsis; " ><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class=" am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['member_name']?></b></label></th>
            
            <th  class="thwrap am-hide-sm-down am-text-center"><?php echo $ld['type']?></th>
            <th  class="ellipsis thwrap am-hide-sm-down"><?php echo $ld['comment_object']?></th>
            <th ><?php echo $ld['comment_content']?></th>
            <th   class="thwrap am-hide-md-down"><?php echo $ld['ip_address']?></th>
            <th    class="thwrap am-hide-md-down"><?php echo $ld['comment_time']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['status']?></th>
            <th ><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($comments_info) && sizeof($comments_info)>0){?>
            <?php foreach($comments_info as $k=>$v){?>
                <tr>
                    <td class="thwrap"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class=" am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Comment']['id']?>" /></span><?php echo isset($user_list[$v['Comment']['user_id']])?$user_list[$v['Comment']['user_id']]:$v['Comment']['name']?></td>
                    
                    <td class="thwrap am-hide-sm-down am-text-center"><?php echo @$v['Comment']['type_name']?></td> 
                    <td class="thwrap am-hide-sm-down" style="word-wrap:break-word;word-break:normal;"><?php echo $v['Comment']['object']?></td>
                    <td class="tableimg" style="word-wrap:break-word;word-break:normal;"><?php echo $v['Comment']['content']?></td>
                    <td class="thwrap am-hide-md-down"><?php echo $v['Comment']['ipaddr']?></td>
                    <td class="thwrap am-hide-md-down"><?php echo $v['Comment']['created']?></td>
                    	
                    <td class="thwrap am-hide-md-down"><?php if($v['Comment']['status']){ echo '<div style="color:#5eb95e" class="am-icon-check"></div>'; }else{ echo '<div style="color:#dd514c" class="am-icon-close"></div>'; } ?></td>
                    <td style="max-width:220px;"><?php
                        if($v['Comment']['status']=='1'){
                            if($v['Comment']['type']=="P"){
                                if($v['Comment']['type_id']!=0){
                                    echo $html->link(' '.$ld['preview'],"/../products/{$v['Comment']['type_id']}",array("class"=>"mt am-icon-eye am-btn am-btn-success am-seevia-btn am-btn-xs am-radius  ","target"=>"_blank")).'&nbsp;&nbsp;';
                                }
                            }
                            if($v['Comment']['type']=="A"){
                                echo $html->link($ld['preview'],"/../articles/{$v['Comment']['type_id']}",array("class"=>"mt am-icon-eye am-btn am-btn-success  am-btn-xs am-radius  am-seevia-btn","target"=>"_blank")).'&nbsp;&nbsp;';
                            }
                        }
                        if($svshow->operator_privilege("comments_edit")&&$v['Comment']['type_id']!=0){
                            echo $html->link($ld['reply'],"/comments/edit/{$v['Comment']['id']}",array("class"=>"mt am-btn am-btn-default  am-btn-xs am-radius")).'&nbsp;&nbsp;';
                        }
                        if($svshow->operator_privilege("comments_remove")){
                            echo $html->link(' '.$ld['remove'],"javascript:;",array("class"=>"mt am-icon-trash-o am-btn am-text-danger am-btn-default am-btn-xs am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){list_delete_submit('{$admin_webroot}comments/remove/{$v['Comment']['id']}');}"));
                        }
                        ?></td>
                </tr>
            <?php }}else{?>
            <tr>
                <td colspan="9" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if($svshow->operator_privilege("comments_remove")){?>
        <?php if(isset($comments_info) && sizeof($comments_info)>0){?>
            <div id="btnouterlist" class="btnouterlist">
                 <div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-hide-sm-down">
                    <label style="margin-top:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b>&nbsp;</label>
                    <input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['batch_delete']?>" onclick="diachange()" />
                </div>
                <div class="am-u-lg-7 am-u-md-7 am-u-sm-12"><?php echo $this->element('pagers')?></div>
                <div class="am-cf"></div>
            </div>
        <?php }?>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function diachange(){
        var id=document.getElementsByName('checkboxes[]');
        var i;
        var j=0;
        var image="";
        for( i=0;i<=parseInt(id.length)-1;i++ ){
            if(id[i].checked){
                j++;
            }
        }
        if( j>=1 ){
            if(confirm("<?php echo $ld['confirm_delete']?>"))
            {
                batch_action();
            }
        }else{
            if(confirm(j_please_select))
            {
                return false;
            }
        }
    }
    
    function batch_action()
    {
        document.ComForm.action=admin_webroot+"comments/batch";
        document.ComForm.onsubmit= "";
        document.ComForm.submit();
    }
    
    function search_user()
    {
        document.UserForm.action=admin_webroot+"comments/";
        document.UserForm.onsubmit= "";
        document.UserForm.submit();
    }
</script>