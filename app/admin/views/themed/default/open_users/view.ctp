<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0">
    <?php if(isset($_SESSION['OPEN_MESSAGE'])):?>
        <span style="color:red;padding:5px;display: block;"><?php echo $_SESSION['OPEN_MESSAGE']; unset($_SESSION['OPEN_MESSAGE']);?></span>
    <?php endif;?>
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information'] ?></a></li>
        <li><a href="#attention"><?php echo $ld['attention_information'] ?></a></li>
        <li><a href="#historical"><?php echo $ld['historical_records']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th><?php echo $ld['avatar'] ?></th>
                        <td><img style="width:60px;height:60px;" src="<?php echo $this->data['OpenUser']['headimgurl']?>"></td>
                    </tr>
                    <tr>
                        <th><?php echo 'OpenId'?></th>
                        <td><?php echo $this->data['OpenUser']['openid']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['open_model'] ?></th>
                        <td><?php echo $this->data['OpenUser']['open_type']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['open_model_account']?></th>
                        <td><?php echo $this->data['OpenUser']['open_type_id']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['nickname']?></th>
                        <td><?php echo urldecode($this->data['OpenUser']['nickname'])?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['gender']?></th>
                        <td><?php echo ($this->data['OpenUser']['sex']==1)?$ld['male']:$ld['female'] ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['language']?></th>
                        <td><?php echo $this->data['OpenUser']['language']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['city']?></th>
                        <td><?php echo $this->data['OpenUser']['city']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['province'] ?></th>
                        <td><?php echo $this->data['OpenUser']['province']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['country']?></th>
                        <td><?php echo $this->data['OpenUser']['country']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['update'].$ld['time']?></th>
                        <td><?php echo $this->data['OpenUser']['created']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['status'] ?></th>
                        <td><?php echo ($this->data['OpenUser']['subscribe']==1)?$ld['attention']:$ld['cancel_attention'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="attention" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['attention_information'] ?></h4>
        </div>
        <div id="attention_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <thead>
                    <tr>
                        <th class="thname"><?php echo $ld['attention'].$ld['type'];?></th>
                        <th class="thname"><?php echo 'CODE';?></th>
                        <th class="thname"><?php echo $ld['attention'].$ld['time'];?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($relationList) && sizeof($relationList)>0){foreach($relationList as $k=>$v){?>
                        <tr>
                            <td><?php echo ($v['OpenRelation']['type'] == 0) ?$ld['product']:$ld['orders'];?></td>
                            <td><?php echo $v['OpenRelation']['type_id']?></td>
                            <td><?php echo $v['OpenRelation']['created']?></td>
                        </tr>
                    <?php }}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="historical" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['historical_records']?></h4>
        </div>
        <div id="historical_records" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <div class="show_border">
                    <div id="showquickreply"></div>
                    <?php if(!empty($msgList)&&sizeof($msgList)>0){?>
                        <?php
                        foreach ($msgList as $k=>$msg){
                            $OpenModel_img=$msg['OpenModel']['img']!=""?$msg['OpenModel']['img']:"http://img.seeworlds.cn/i/2012/06/img_ioco01_com/www.seevia.cn/original/1/138d541a400a1eb2cb3c77ec829280d1f.jpg";
                            ?>
                            <?php if($msg['OpenUserMessage']['send_from'] == 1){?>
                                <?php if($msg['OpenUserMessage']['return_message']!=""){//系统进行了回复  ?>
                                    <div style="margin:5px;clear:both;height:80px;">
                                        <div style="float:right;margin:5px;"><img src="<?php echo $OpenModel_img; ?>" style="width:60px;height:60px;"></div>
                                        <div style="max-width:50%;float:right;margin-top:15px;padding:10px;border:1px solid #E1ECCE;background:none repeat scroll 0 0 #E9F8D9;">
                                            <?php echo $msg['OpenUserMessage']['return_message'];?>
                                            <div style="margin:5px 0px"><?php echo $msg['OpenUserMessage']['created']?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div style="margin:5px;clear:both;height:80px;">
                                    <div style="float:left;margin:5px;"><img src="<?php echo $this->data['OpenUser']['headimgurl']?>" style="width:60px;height:60px;"></div>
                                    <div style="max-width:50%;float:left;margin-top:15px;padding:10px;border:1px solid #E1ECCE;background:none repeat scroll 0 0 #E9F8D9;">
                                        <?php if($msg['OpenUserMessage']['msgtype'] == 'image') {?>
                                            <a href="<?php echo $msg['OpenUserMessage']['message']?>" target="_blank"><img src="<?php echo $msg['OpenUserMessage']['message']?>" style="width:60px;height:60px;"></a>
                                        <?php }else{ echo $msg['OpenUserMessage']['message'];}?>
                                        <div style="margin:5px 0px"><?php echo $msg['OpenUserMessage']['created']?></div>
                                    </div>
                                </div>
                            <?php }else{?>
                                <div style="margin:5px;clear:both;height:80px;">
                                    <div style="float:right;margin:5px;"><img src="<?php echo $OpenModel_img; ?>" style="width:60px;height:60px;"></div>
                                    <div style="max-width:50%;float:right;margin-top:15px;padding:10px;border:1px solid #E1ECCE;background:none repeat scroll 0 0 #E9F8D9;">
                                        <?php echo $msg['OpenUserMessage']['message'];?>
                                        <div style="margin:5px 0px"><?php echo $msg['OpenUserMessage']['created']?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php }?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        <?php if($this->data['OpenUser']['subscribe']){ 
            echo "showquickreply(".$this->data['OpenUser']['id'].");";
        } ?>
    })

    function showquickreply(Id){
        $.ajax({ url: "/admin/open_users/quickreply/"+Id,
            type:"POST",
            data:{},
            dataType:"html",
            success: function(data){
                $("#showquickreply").html(data);
            }
        });
    }
</script>