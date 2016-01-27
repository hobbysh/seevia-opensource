 
<p class="am-u-md-12">	<a href="/admin/votes"><input  type="submit"class=" am-btn am-btn-default am-btn-xs am-radius am-fr" value="<?php echo $ld['votes']  ?>"/></a></p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th><?php echo $ld['user_name']?></th>
            <th><?php echo $ld['subject']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['ip_address']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['operating_system']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['growser_version']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['resolution']?></th>
            <th><?php echo $ld['vote_content']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['fornt_valid']?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($vote_logs_list) && sizeof($vote_logs_list)>0){
            foreach($vote_logs_list as $k=>$v ){?>
                <tr>
                    <td><?php echo empty($new_user_list[$v['VoteLog']['user_id']])?$ld['user_or_visitor']:$new_user_list[$v['VoteLog']['user_id']];?></td>
                    <td><?php echo @$new_vote_list[$v['VoteLog']['vote_id']]?></td>
                    <td class="thwrap am-hide-md-down"><?php echo @$v['VoteLog']['ip_address']?></td>
                    <td class="thwrap am-hide-md-down"><?php echo @$v['VoteLog']['system']?></td>
                    <td class="thwrap am-hide-md-down"><?php echo @$v['VoteLog']['browser']?></td>
                    <td class="thwrap am-hide-md-down"><?php echo @$v['VoteLog']['resolution']?></td>
                    <td><?php
                        if(isset($v['VoteLog']['vote_option_id_arr']) && sizeof($v['VoteLog']['vote_option_id_arr'])>0){
                            foreach($v['VoteLog']['vote_option_id_arr'] as $vv){
                                echo "<p>".@$new_voteoption_list[$vv]."</p>";
                            }	}
                        ?></td>
                    <td class="thwrap am-hide-md-down"><?php
                        if ($v['VoteLog']['status'] == 1){
                            echo $html->image('yes.gif',array('align'=>'absmiddle','onclick'=>''));
                        }elseif($v['VoteLog']['status'] == 0){
                            echo $html->image('no.gif',array('align'=>'absmiddle','onclick'=>''));
                        }
                        ?></td>
                    <td><?php
                        echo $html->link($ld['remove'],"javascript:;",array("class"=>"am-btn am-btn-default am-radius am-btn-sm am-text-secondary","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}votes/vote_logs_remove/{$v['VoteLog']['id']}';}"));
                        ?></td>
                </tr>
            <?php } ?>
        <?php }else{?>
            <tr>
                <td colspan="9" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>