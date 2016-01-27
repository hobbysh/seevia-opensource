<p class="am-u-md-12 am-text-right am-btn-group-xs" >
    <?php
        if($svshow->operator_privilege("email_lists_add")){
            echo $html->link($ld['magazine'].$ld['magazine_user'],'/newsletter_lists/',array('target'=>"_blank","class"=>"am-btn am-radius am-btn-sm  am-btn-default"));
            echo "&nbsp;";
            echo $html->link($ld['subscriber'],"email_flag_user/",array('target'=>"_blank","class"=>"am-btn am-btn-default am-radius am-btn-sm "),false,false);
        }
    ?><?php if($svshow->operator_privilege("email_lists_add")){  ?>
          <a  class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('add/'); ?>">
         <span class="am-icon-plus"></span>
         <?php echo $ld["magazine_add"] ;?>
         </a>
        <?php  }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="tablelist" class="am-table  table-main">
        <thead>
        <tr>
            <th><?php echo $ld['magazine_title']?></th>
            <th><?php echo $ld['last_edited_time']?></th>
            <th><?php echo $ld['last_send_time']?></th>
            <th style="width:250px;"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($MailTemplate_list) && sizeof($MailTemplate_list)>0){?>
            <?php foreach( $MailTemplate_list as $k=>$v ){ ?>
                <tr>
                    <td><?php echo $v["MailTemplateI18n"]["title"]?></td>
                    <td><?php echo $v["MailTemplate"]["modified"]?></td>
                    <td><?php echo $v["MailTemplate"]["last_send"]?></td>
                    <td class="am-action"><?php if($svshow->operator_privilege("email_lists_edit")){?>
                        <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/email_lists/edit/'.$v['MailTemplate']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php 	}?>
                        <?php if($svshow->operator_privilege("email_lists_remove")){?>
                            <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'/email_lists/remove/<?php echo $v['MailTemplate']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a>
                        <?php }?></td>
                </tr>
            <?php }}else{?>
            <tr>
                <td colspan="4" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($MailTemplate_list) && sizeof($MailTemplate_list)>0){?>
        <div id="btnouterlist" class="btnouterlist">
            <?php echo $this->element('pagers')?>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function GetId(id){
        return document.getElementById(id);
    }

    function newsletter_email(num){
        var usermode = GetId("usermode"+num);
        var toppri = GetId("toppri"+num);
        window.location.href = admin_webroot+"email_lists/insert_email_queue/"+usermode.value+"/"+toppri.value+"/"+num+"/";
    }
</script>