<?php
/*****************************************************************************
 * SV-Cart  会员等级管理列表
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
<?php if($svshow->operator_privilege("user_groups_add")){?>
    <p class="am-u-md-12 am-text-right am-btn-group-xs"style="margin-right:90px;">
          <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('/user_groups/view'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'].$ld["user_group"] ?>
          </a>
    </p>
<?php }?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <?php echo $form->create('user_groups',array('action'=>'/removeAll','name'=>'UserGroupForm','type'=>'post',"onsubmit"=>"return false;"));?>
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['name'];?></b></label></th>
            <th><?php echo $ld['description'];?></th>
            <th><?php echo $ld['status'];?></th>
            <th style="width:200px;"><?php echo $ld['operate'];?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($user_group_list)&&count($user_group_list)>0){foreach($user_group_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['UserGroup']['id']?>" /></span><?php echo $v['UserGroup']['name'];?></td>
                <td><?php echo $v['UserGroup']['description']; ?></td>
                <td>
                    <?php if ($v['UserGroup']['status'] == 1){?>
                        <div style="color:#5eb95e" class="am-icon-check"></div>
                    <?php }else{?>
                        <div style="color:#dd514c" class="am-icon-close"></div>
                    <?php }?>
                </td>
                <td class="am-action">
                    <?php if($svshow->operator_privilege("user_groups_edit")){?>
                            <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_groups/'.$v['UserGroup']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                   <?php  }?><?php if($svshow->operator_privilege("user_groups_remove")){?>
                        <a class="am-btn am-btn-default am-text-danger am-btn-sm am-radius" href="/admin/user_groups/remove/<?php echo $v['UserGroup']['id']; ?>" onclick="return confirm('<?php echo $ld['confirm_delete'] ?>');"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a>
                    <?php }?>
                </td>
            </tr>
        <?php }}else{ ?>
            <tr>
                <td colspan="9"class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }	?>
        </tbody>
    </table>
    <?php if(isset($user_group_list)&&count($user_group_list)>0){ ?>
    <div id="btnouterlist" class="btnouterlist">
        <?php if($svshow->operator_privilege("user_groups_remove")){?>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-hide-sm-down"style="margin-left:1px;">
                <label style="margin-right:5px;float:left; margin-top:6px;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
                    <span><?php echo $ld['select_all'] ?></span>
                </label>
                <input type="button" onclick="removeAll()" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['batch_delete'] ?>" />
            </div>
        <?php }?>
            <div class="am-u-lg-8 am-u-md-7 am-u-sm-12"><?php echo $this->element('pagers'); ?></div>
            <div class="am-cf"></div>
    </div>
    <?php } echo $form->end(); ?>
</div>
<script type="text/javascript">
    window.onload=function(){
        document.getElementById("tablelist").style.display="block";
    };

    //批量删除
    function removeAll()
    {
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
            if(confirm('确认删除？'))
            {
                document.UserGroupForm.submit();
            }
        }
    }
</script>