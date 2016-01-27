<?php echo $form->create('',array('action'=>'/batch_user_print/',"name"=>"UserForm",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="t1" class="am-table  table-main">
        <thead>
	        <tr>
	            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['user_reffer'];?></b></label></th>
	            <th style="width:200px;"><?php echo $ld['name_of_member'];?></th>
	            <th><?php echo $ld['member_name']?></th>
	            <th><?php echo $ld['email']?></th>
	            <th><?php echo $ld['mobile']?></th>
	            <th><?php echo $ld['status']?></th>
	            <th><?php echo $ld['discount']?></th>
	            <th><?php echo $ld['registration_time']?></th>
	            <th><?php echo $ld['subscriber']?></th>
	            <th><?php echo $ld['operate']?></th>
	        </tr>
        </thead>
        <tbody>
        <?php if(isset($users_list) && sizeof($users_list)>0){foreach($users_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['User']['id']?>" />
                        <?php  if(isset($order_type_arr[$v['User']['type']])){
                            if(isset($v['User']['type'])&&$v['User']['type']=='dealer'){
                                $type_id_name=isset($dealers_list[$v['User']['user_type_id']])?$dealers_list[$v['User']['user_type_id']]:$v['User']['user_type_id'];
                                if($type_id_name=='网站'){
                                    echo $order_type_arr[$v['User']['type']]."-".$ld['website'];}else
                                {
                                    echo $order_type_arr[$v['User']['type']]."-".$type_id_name;
                                }
                            }else{
                                if($v['User']['type_id']=='网站'){
                                    echo $order_type_arr[$v['User']['type']]."-".$ld['website'];}else
                                {
                                    echo $order_type_arr[$v['User']['type']]."-".$v['User']['user_type_id'];
                                }
                            }
                        }else{if(isset($v['User']['type'])&&$v['User']['type']=='dealer'&&isset($order_type['dealer'][$v['User']['user_type_id']])){echo $order_type['dealer'][$v['User']['user_type_id']];}else{echo $v['User']['user_type_id'];}}?>
                </td>
                <td>
                    <?php if(isset($user_order_infos[$v['User']['id']])){?>
                        <span onclick="showorder(this);return false;" ><?php printf($ld["order_num_of_user"],count($user_order_infos[$v['User']['id']]));?>&#0187;</span>
                        <div>
                        </div>
                    <?php }?>
                    <?php echo $v['User']['first_name']?>
                </td>
                <td><?php echo $v['User']['name']?></td>
                <td><?php
                    if($v['User']['email']==""){
                        echo '';
                    }else{
                        echo "<span style='min-width: 110px;display: inline-block;'>";
                        echo $v['User']['email'];
                        echo "</span>";
                        echo "<span style='padding: 0 3px;color: #666;'>";
                        echo ($v['User']['verify_status']==1)?$ld['status_certified']:$ld['status_uncertified'];
                        echo "</span>";
                    }
                    ?></td>
                <td><?php echo $v['User']['mobile']?></td>
                <td><?php
                    if($v['User']['status']==0){
                        echo $ld['invalid'];
                    }elseif($v['User']['status']==1){
                        echo $ld['valid'];
                    }elseif($v['User']['status']==2){
                        echo $ld['status_frozen'];
                    }elseif($v['User']['status']==3){
                        echo $ld['status_logout'];
                    }?></td>
                <td><?php echo $v['User']['admin_note2']?></td>
                <td><?php $time=explode(" ",$v['User']['created']);echo $time[0].'<br>'.$time[1];?></td>
                <td><?php if(isset($v['User']['email_flag'])){
                        if($v['User']['email_flag']==0){echo '未订阅';}
                        if($v['User']['email_flag']==1){echo '订阅中';}
                        if($v['User']['email_flag']==2){echo '已订阅';}
                    } ?></td>
                <td><?php
                    if($svshow->operator_privilege("users_edit")){
                        echo $html->link($ld['edit'],"/users/{$v['User']['id']}",array("class"=>"am-btn am-btn-success am-btn-xs am-radius")).'&nbsp;&nbsp;';
                    }
                    if($svshow->operator_privilege("users_remove")){
                        echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-xs am-radius","onclick"=>"if(confirm('{$ld['confirm_delete_user']}')){list_delete_submit('{$admin_webroot}users/remove/{$v['User']['id']}');}"));
                    }
                    ?></td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="10"  class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($users_list) && sizeof($users_list)){?>
        <div id="btnouterlist" class="btnouterlist">
            <?php echo $this->element('pagers')?>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>