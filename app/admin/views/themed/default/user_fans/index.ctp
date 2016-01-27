<style>

</style> 
 <div class="listsearch">
    <?php
    echo $form->create('UserFans',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-inline am-form-horizontal'));
    ?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-4 am-u-md-4  am-u-lg-3 am-form-label"><?php echo $ld['user_name'] ?></label>
            <div class="am-u-sm-7 am-u-md-7  am-u-lg-7 " style="padding:0 0.5rem;">
                <input type="text" name="keyword" id="blog_keyword" value="<?php echo isset($keyword)?$keyword:"";?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-4  am-u-md-4 am-u-lg-4  am-form-label"><?php echo $ld['user_last_login_time']; ?></label>
            <div class="am-u-sm-3 am-u-md-3 am-u-lg-3  " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
            </div>
            <em class=" am-u-sm-1 am-u-md-1 am-u-lg-1  am-text-center " style="padding-top:5px;" >-</em>
            <div class="am-u-sm-3 am-u-md-3  am-u-lg-3  am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
        	 <label class="am-u-sm-5  am-u-md-4  am-form-label  "> </label>
            <div class="am-u-sm-4  am-u-md-4 " style="padding:0 0.5rem;">
                <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>" />
            </div>
        </li>
    </ul>
    <input type="hidden" name="blogId" value='<?php echo isset($blogId)?$blogId:""; ?>' />
    <?php
    echo $form->end();
    ?>
</div>
<p class=" am-btn-group-xs am-text-right " >
    <?php if($svshow->operator_privilege("userchat_view")){echo $html->link($ld['private_letter_management'],"/users/userchat",array("class"=>"am-btn am-radius am-btn-default  am-btn-sm "));}?>
</p>
<div id="tablelist" class="tablelist  ">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th style="width:50px;text-align:center;"><?php echo $ld['avatar'] ?></th>
            <th width='150px' style="text-align:center;"><?php echo $ld['user_name'] ?></th>
            <th style="text-align:center;"class="am-hide-sm-down"><?php echo $ld['fans_number'] ?></th>
            <th style="text-align:center;" class="am-hide-sm-down"><?php echo $ld['focus_number'] ?></th>
            <th style="text-align:center;"  class="am-hide-sm-down"><?php echo $ld['blog_number'] ?></th>
            <th style="width:150px;"><?php echo $ld['operate'] ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($userInfo) && !empty($userInfo)){
            foreach($userInfo as $k=>$v)
            {
                ?>
                <tr>
                    <td style="width:50px;height:40px;text-align:center;padding:5px 0;"><img class='UserImg' src="<?php echo $v['User']['img01']!=''?$v['User']['img01']:'/theme/AmazeUI/img/no_head.png'; ?>" style="border:0px;" onload="set_img(this,40,40)"/></td>
                    <td align="center"><?php echo $v["User"]["name"]; ?></td>
                    <td align="center" class="am-hide-sm-down"><?php echo isset($v["User"]["fancount"])?$v["User"]["fancount"]:0; ?></td>
                    <td align="center" class="am-hide-sm-down"><?php echo isset($v["User"]["attentioncount"])?$v["User"]["attentioncount"]:0; ?></td>
                    <td align="center" class="am-hide-sm-down"><?php echo isset($v["User"]["diarycount"])?$v["User"]["diarycount"]:0; ?></td>
                    <td><a class="am-btn am-btn-success am-seevia-btn am-btn-xs am-radius" href='/admin/user_fans/showDetailed/<?php echo $v["User"]["id"]; ?>'> <span class="am-icon-eye"></span> <?php echo $ld['views'] ?></a></td>
                </tr>
            <?php
            }
        }else
        {
            ?>
            <tr>
                <td colspan="8" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php
if(isset($userInfo) && !empty($userInfo)){
    ?>
    <div id="btnouterlist" class="btnouterlist">
        <?php
        //打印分页信息
        echo $this->element('pagers');
        ?>
    </div>
<?php } ?>
<script>
    $(function(){
        set_img($(".UserImg"),40,40);
    })
    function set_img(obj,wid,hei){
     
        var img_src=obj.src;
        var img=new Image();
        img.src=img_src;
        img.onload=function(){
               
              if(img.width>wid){
                img_w=wid;
            }else{
                img_w=img.width;
            }
            if(img.height>hei){
                img_h=hei;
            }else{
                img_h=img.height;
            }
 
            if(img.width/img.height>=wid/hei)
            {
                if(img.width > img_w)
                {   
                      $('.UserImg').css({"width":"40px"});
                      $('.UserImg').css("height","40px");
  		  
                }else{ 
                    $('.UserImg').css("width",img.width+"px");
                    $('.UserImg').css("height",img.height+"px");
                }
            }else{ 
                if(img.height > img_h)
                {
                    $('.UserImg').css("height",img_h+"px");
                    $("img").css("width","10"+"px");
                }else{
                    $('.UserImg').css("width",img.width+"px");
                    $('.UserImg').css("height",img.height+"px");
                }
            }
        }
    }
</script>