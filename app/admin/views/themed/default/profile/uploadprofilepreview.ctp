<?php echo $form->create('profiles',array('action'=>'/batch_add_profiles/','name'=>"theForm"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="t1" class="am-table  table-main">
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['number']?></b></label></th>
            <?php foreach($profilefiled_info as $thk => $thv){?>
                <th><?php echo $thv['ProfilesFieldI18n']['description'];?></th>
            <?php }?>
        </tr>
        <?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue;?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $k?>" /><?php echo $k;?></label></td>
                <?php foreach($profilefiled_info as $kk => $vv){
                    $fields_kk=$vv['ProfileFiled']['code'];
                    $fields_kk_arr=explode('.',$fields_kk);
                    ?>
                    <td><input type='text' name="data[<?php echo $k?>][<?php echo $fields_kk_arr[0]; ?>][<?php echo $fields_kk_arr[1]; ?>]" value="<?php echo isset($v[$fields_kk])?$v[$fields_kk]:"";?>" /></td>
                <?php }?>
            </tr>
        <?php }}?>
    </table>
    <div id="btnouterlist" class="btnouterlist" style="margin-left:0;">
        <div>
            <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
            <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
        </div>
    </div>
</div>
<?php $form->end();?>
<script type="text/javascript">
    $(function){
        if(document.getElementById('msg')){
            var msg =document.getElementById('msg').value;
            if(msg !=""){
                alert(msg);
                var button=document.getElementById('btnouterlist');
                button.style.display="none";
            }
        }
    }
</script>