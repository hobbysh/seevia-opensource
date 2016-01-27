 <style>
.am-form-label{font-weight:bold;text-align:center;margin-top:5px;margin-left:17px;}	
</style>
<div class="listsearch">
    <?php echo $form->create('OpenKeyword',array('action'=>'/','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['open_model']?></label>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6" style="padding:0 0.5rem;">
                <select id='OpenModelType' name='openType' data-am-selected="{noSelectedText:''}">
                    <option value='wechat' <?php if (isset($openType) && $openType == 'wechat') echo 'selected'; ?>><?php echo $ld['wechat'] ?></option>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['open_model_account']?></label>
            <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                <select name="open_type_id" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?>'}">
                    <option value=""><?php echo $ld['all_data'] ?></option>
                    <?php foreach($openmodel_list as $k=>$v){ ?>
                        <option value="<?php echo $v['OpenModel']['open_type_id'] ?>"><?php echo $v['OpenModel']['open_type_id'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
                <input placeholder="<?php echo $ld['keyword']?>" type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
         <label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"> </label>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
                <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/>
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<p class="am-u-md-12 am-text-right am-btn-group-xs">
	<?php if($svshow->operator_privilege("open_keywords_add")){?>
				<a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view/'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['add'] ?>
				</a>
<?php }?>
</p>
<?php echo $form->create('OpenKeyword',array('action'=>'/remove/','name'=>'OpenCallKeywordForm','type'=>'get',"onsubmit"=>"return false;"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="tablelist" class="am-table  table-main">
        <thead>
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['open_model'];?></b></label></th>
            
            <th><?php echo $ld['open_model_account'];?></th>
            <th><span id="edit"></span><?php echo $ld['keyword']?></th>
            <th class="am-hide-sm-down"><?php echo $ld['type']?></th>
            <th class="am-hide-sm-down"><?php echo $ld['status']; ?></th>
            <th style="width:250px;"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($key_list) && sizeof($key_list)>0){foreach($key_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OpenKeyword']['id']?>" /></span><?php echo ($v['OpenKeyword']['open_type'] == 'wechat')?$ld['wechat']: $v['OpenKeyword']['open_type'];?></label></td>
             
                <td><?php echo $v['OpenKeyword']['open_type_id']?></td>
                <td class="am-hide-sm-down"><div style="height:auto;white-space: normal;"><?php echo $v['OpenKeyword']['keyword']?></div></td>
                <td class="am-hide-sm-down"><?php if($v['OpenKeyword']['match_type']==0){echo $ld['fuzzy_matching'];}else{echo $ld['perfect_matching'];}?></td>
                <td>
                    
                    <?php if($svshow->operator_privilege("open_keywords_edit")){
                        if($v['OpenKeyword']['status']=='1'){
                            echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"open_keywords/toggle_on_status",'.$v["OpenKeyword"]["id"].')></div>';
                        }else{
                            echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"open_keywords/toggle_on_status",'.$v["OpenKeyword"]["id"].')></div>';
                        }
                    }else{
                        if($v['OpenKeyword']['status']=='1'){
                            echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                        }else{
                            echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                        }
                    } ?>
                </td>
                <td class="am-action">
                    <?php if($svshow->operator_privilege("open_keywords_edit")){?><a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/open_keywords/view/'.$v['OpenKeyword']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php } ?><?php if($svshow->operator_privilege("open_keywords_remove")){?>
                            <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){remove1('<?php echo $v['OpenKeyword']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a><?php } ?>
                            </td>
            </tr>
        <?php }}else{
            $noo=1;
            ?>
            <tr>
                <td colspan="9" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div id="btnouterlist" class="btnouterlist" style="<?php if(isset($noo)&&$noo==1){echo 'display:none';} ?>">
        <?php if($svshow->operator_privilege("open_keywords_remove")){?>
            <div class="am-u-lg-6 am-u-md-4 am-u-sm-6 am-hide-sm-down">
                <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
                    <b><?php echo $ld['select_all']?></b>
                </label>
                <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" onclick="removeAll()" value="<?php echo $ld['batch_delete']?>" />
            </div>
        <?php }?>
        <div class="am-u-lg-6 am-u-md-7 am-u-sm-12 am-fr"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
</div>
<?php echo $form->end()?>
<script type="text/javascript">
    function checkbox(){
        var str=document.getElementsByName("box");
        var leng=str.length;
        var chestr="";
        for(i=0;i<leng;i++){
            if(str[i].checked == true)
            {
                chestr+=str[i].value+",";
            };
        };
        return chestr;
    };

    function removeAll(){
        var ck=document.getElementsByName('checkboxes[]');
        var j=0;
        for(var i=0;i<=parseInt(ck.length)-1;i++)
        {
            if(ck[i].checked)
            {
                j++;
            }
        }
        if(j>=1){
            if(confirm("<?php echo $ld['confirm_delete'] ?>"))
            {
                document.OpenCallKeywordForm.action=admin_webroot+"open_keywords/remove/";
                document.OpenCallKeywordForm.onsubmit= "";
                document.OpenCallKeywordForm.submit();
            }
        }
    }

    function remove1(id){
        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
            var func="open_keywords/remove";
            var sUrl = admin_webroot+func;//访问的URL地址
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {id: id},
                success: function (result) {
                    if(result.flag == 1){
                        alert(j_deleted_success);
                        window.location.reload();
                    }
                    if(result.flag == 2){
                        alert(result.message);
                    }
                }
            });
        }
    }
</script>