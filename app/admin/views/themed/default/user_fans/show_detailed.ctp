<p class="am-u-md-12">
    <a class="am-btn am-radius am-btn-sm am-fr" href="javascript:history.go(-<?php echo $his; ?>);"><?php echo $ld['back']?></a>
    <a class="am-btn am-radius am-btn-sm am-fr" href='/admin/user_fans/showDetailed/<?php echo $id."?order=".$order."&his=".$his; ?>'><?php echo $orderType; ?></a>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th width="50%"><?php echo $ld['fan_head']?></th>
            <th><?php echo $ld['focus_head']?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <div style="width:99%;white-space:normal;">
                    <?php
                    if(isset($fans)&&sizeof($fans)>0){
                        foreach($fans as $k=>$v)
                        {
                            ?>
                            <img class='UserImg' src='<?php echo $v["UserFan"]["img"]!=""?$v["UserFan"]["img"]:"/theme/AmazeUI/img/no_head.png"; ?>' title='<?php echo $v["UserFan"]["name"]; ?>' onload="set_img(this,40,40)" />&nbsp;
                        <?php
                        }
                    }
                    else
                    {
                        echo "<font color='red'>暂无粉丝</font>";
                    }
                    ?>
                </div>
            </td>
            <td>
                <div style="width:99%;white-space:normal;">
                    <?php
                    if(isset($att)&&count($att)>0){
                        foreach($att as $k=>$v){
                            ?>
                            <img class='UserImg' src='<?php echo $v["UserFan"]["img"]!=""?$v["UserFan"]["img"]:"/theme/AmazeUI/img/no_head.png"; ?>' title='<?php echo $v["UserFan"]["name"]; ?>' onload="set_img(this,40,40)" />
                        <?php
                        }
                    }
                    else{
                        echo "<font color='red'>暂未关注任何人</font>";
                    }
                    ?>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
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
            if(img.width/img.height >= img_w/img_h)
            {
                if(img.width > img_w)
                {
                    $(obj).css("width",img_w+"px");
                    $(obj).css("height",((img.height*img_w) / img.width)+"px");
                }else{
                    $(obj).css("width",img.width+"px");
                    $(obj).css("height",img.height+"px");
                }
            }else{
                if(img.height > img_h)
                {
                    $(obj).css("height",img_h+"px");
                    $(obj).css("width",((img.width * img_h) / img.height)+"px");
                }else{
                    $(obj).css("width",img.width+"px");
                    $(obj).css("height",img.height+"px");
                }
            }
        }
    }
</script>