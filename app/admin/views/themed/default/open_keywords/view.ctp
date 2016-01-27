<style type="text/css">
.am-radio, .am-checkbox{display:inline;}
.am-checkbox {margin-top:0px; margin-bottom:0px;}
label{font-weight:normal;}
.am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}

.fs12{font-size:12px;}
.input_detail{resize:none;height:78px; width:511px;top:13px;position:relative;left:7px;overflow:hidden;}
.text_center{text-align:center;}
.w550h162{border:1px solid #ccc;width:550px;height:162px;}
.w550h40{border-bottom:1px solid #ccc;width:550px;height:40px;}
.face{cursor:pointer;margin-left:18px;line-height:40px;}
.face img{position:relative;top:0px;left:0px;}
.expression{width:250px;display:none; position:relative;top: -25px;left:5px;}
.picks{width:25px;height:25px; float:left; border:1px solid #CECECE;background:#fff;}
#biaoqingnew{cursor:pointer;margin-left:18px;line-height:40px;}
#biaoqingnew img{position:relative;top:4px;left:0px;}
</style>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <?php if($id!=0){?>
            <li><a href="#reply"><?php echo $ld['custom'].$ld['reply']?></a></li>
        <?php }?>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" >
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <?php echo $form->create('OpenKeyword',array('action'=>'/view/'.$id,'name'=>"KeywordFormview",'id'=>"KeywordFormview",'onsubmit' =>'return check_page_style()'));?>
            <input id="ide" type="hidden" value="<?php echo $id;?>" name="data[OpenKeyword][id]"/>
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['open_model']?></th>
                        <td><select data-am-selected="{noSelectedText:''}" name="data[OpenKeyword][open_type]">
                                <option value='wechat' <?php if (isset($this->data['OpenKeyword'])&&$this->data['OpenKeyword']['open_type']=='wechat') echo 'selected'; ?>><?php echo $ld['wechat'] ?></option>
                            </select></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['open_model_account']?></th>
                        <td><select data-am-selected="{noSelectedText:''}" name="data[OpenKeyword][open_type_id]">
                                <option value=""><?php echo $ld['open_model_account'] ?></option>
                                <?php foreach($openmodel_list as $k=>$v){ ?>
                                    <option value="<?php echo $v['OpenModel']['open_type_id'] ?>" <?php if ((isset($this->data['OpenKeyword'])&&$this->data['OpenKeyword']['open_type_id']==$v['OpenModel']['open_type_id'])||($k==0)) echo 'selected'; ?>><?php echo $v['OpenModel']['open_type_id'] ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['keyword']?></th>
                        <td>
                            <input type="text" style="width:200px;" class="border" id="key1" name="data[OpenKeyword][keyword]" value="<?php if(isset($this->data['OpenKeyword'])){echo $this->data['OpenKeyword']['keyword'];} ?>" onchange="distinctkw(this,<?php echo $id;?>)" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="fs12"><em style="top:1px;">*</em><?php echo $ld['open_keyword_empty'] ?></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['type']?></th>
                        <td>
                            <select data-am-selected="{noSelectedText:''}" id="seltype" class="border" name="data[OpenKeyword][match_type]" value="<?php if(isset($this->data['OpenKeyword']['match_type'])){echo $this->data['OpenKeyword']['match_type'];} ?>">
                                <option value="0" <?php if($this->data['OpenKeyword']['match_type']=="0"){echo "selected";}?>><?php echo $ld['fuzzy_matching']; ?></option>
                                <option value="1" <?php if($this->data['OpenKeyword']['match_type']=="1"){echo "selected";}?>><?php echo $ld['perfect_matching']; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="fs12"><em style="top:1px;">*</em><?php echo $ld['open_keyword_type_empty'] ?></td>
                    </tr>
                    <tr>
                        <th style="padding-top:16px;"><?php echo $ld['status'] ?></th>
                        <td>
                            <label class="am-radio am-success" style="padding-top:2px"><input type='radio' name="data[OpenKeyword][status]" value='1' data-am-ucheck <?php echo (isset($this->data['OpenKeyword']['status'])&&$this->data['OpenKeyword']['status']=='1')||!isset($this->data['OpenKeyword'])?'checked':'' ?> /><?php echo $ld['valid_status'] ?></label>
                            <label class="am-radio am-success" style="margin-left:10px;padding-top:2px;"><input type='radio' name="data[OpenKeyword][status]" value='0' data-am-ucheck <?php echo isset($this->data['OpenKeyword']['status'])&&$this->data['OpenKeyword']['status']=='0'?'checked':'' ?> /><?php echo $ld['account_number_invalid_state'] ?></label>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" onclick="check_page_style()" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
            <?php echo $form->end();?>
        </div>
    </div>
    <?php if($id!=0){?>
        <div id="reply" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title"><?php echo $ld['custom'].$ld['reply']?></h4>
            </div>
            <div id="custom_reply" class="am-panel-collapse am-collapse am-in">
                <div class="tablelist am-u-md-12 am-u-sm-12" style="padding-bottom:0;">
                    <p class="am-u-md-12" style="margin-top:8px;"><a href="javascript:;" class="am-btn am-btn-warning am-btn-sm am-fr" data-am-modal="{target: '#answer', closeViaDimmer: 0, width: 745, height: 450}"><?php echo $ld['add']?></a></p>
                    <?php echo $form->create('OpenKeyword',array('action'=>'/removeanswer/','name'=>'OpenCallKeywordForm','id'=>"OpenCallKeywordKeywordForm",'type'=>'get',"onsubmit"=>"return false;"));?>
                    <input id="OpenKeyword_id" type="hidden" value="<?php echo $id;?>" name="OpenKeyword_id"/>
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['number']?></b></label></th>
                            <th><?php echo $ld['reply']?></th>
                            <th><?php echo $ld['type']?></th>
                            <th><?php echo $ld['status']; ?></th>
                            <th style="width:150px;"><?php echo $ld['operate']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($key_list) && sizeof($key_list)>0){foreach($key_list as $k=>$v){?>
                            <tr>
                                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OpenKeywordAnswer']['id']?>" /><?php echo $v['OpenKeywordAnswer']['id']?></label></td>
                                <td><?php if($v['OpenKeywordAnswer']['msgtype']=="text"){echo $v['OpenKeywordAnswer']['message'];}else{ echo $svshow->link($key_list[$k]['title'],"/open_elements/preview/{$v['OpenKeywordAnswer']['element_id']}",array('class'=>'aresponse',"target"=>"_blank"));}?></td>
                                <td><?php if($v['OpenKeywordAnswer']['msgtype']=="text"){echo $ld['word'];}else{echo $ld['graphics'];}?></td>
                                <td>
                                    <?php if($v['OpenKeywordAnswer']['status']==1){
                                        echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"open_keywords/toggle_on_answer_status",'.$v['OpenKeywordAnswer']["id"].')></div>';
                                    }else{
                                        echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"open_keywords/toggle_on_answer_status",'.$v['OpenKeywordAnswer']["id"].')></div>';
                                    } ?>
                                </td>
                                <td><a class="am-btn am-btn-default  am-btn-xs am-radius" style="color: #3bb4f2;" href="javascript:void(0);" data-am-modal="{target: '#answer<?php echo $v['OpenKeywordAnswer']['id']?>', closeViaDimmer: 0, width: 745, height: 450}"><?php echo $ld['edit'];?></a>&nbsp;&nbsp;<?php if($svshow->operator_privilege("open_keywords_remove")){?><a class="am-btn am-btn-default am-text-danger am-btn-xs am-radius"  href="javascript:;" onclick='remove1(<?php echo $v['OpenKeywordAnswer']['id'];?>)'><?php echo $ld['delete'];?></a><?php }?></td>
                            </tr>
                        <?php }}else{
                            $noo=1;
                            ?>
                            <tr>
                                <td colspan="5" class="no_data_found"><?php echo $ld['no_data_found']?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <div id="btnouterlist" class="btnouterlist" style="<?php if(isset($noo)&&$noo==1){echo 'display:none';} ?>">
                        <div>
                            <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
                                <b><?php echo $ld['select_all']?></b>
                            </label>
                            <input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" onclick="removeAll()" value="<?php echo $ld['batch_delete']?>" />
                        </div>
                    </div>
                    <?php echo $form->end();?>
                </div>
            </div>
        </div>
    <?php }?>
</div>


<div class="am-modal am-modal-no-btn" tabindex="-1" id="answer" name="answer">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['reply'].$ld['add']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <?php echo $form->create('OpenKeyword',array('action'=>'/viewanswer/'.$id,'name'=>"KeywordFormnew",'id'=>"KeywordFormnew"));?>
        <input type="hidden" name="data[OpenKeywordAnswer][id]" value="" />
        <div class="am-modal-bd">
            <table class="am-table" style="text-align:left;">
                <tr>
                    <th><?php echo $ld['keyword']?></th>
                    <td>
                        <input type="text" class="border" disabled="disabled" style="background:#ccc;" name="key" value="<?php echo $keyword ?>" />
                        <input type="hidden" name="data[OpenKeywordAnswer][keyword_id]" value="<?php echo $id ?>" />
                    </td>
                </tr>
                <tr>
                    <th><?php echo $ld['reply'].$ld['type']?></th>
                    <td>
                        <select id="seltypenew" onchange="showhide('new')" class="border" name="data[OpenKeywordAnswer][msgtype]" value="" >
                            <option value="text" ><?php echo $ld['word']; ?></option>
                            <option value="picture"><?php echo $ld['graphics']; ?></option>
                        </select>
                    </td>
                </tr>
                <tr id="picresourcenew" style="display:none;">
                    <th><?php echo $ld['graphic_resources']; ?></th>
                    <td>
                        <select id="typenew" class="border" name="data[OpenKeywordAnswer][element_id]" value="">
                            <option value=""><?php echo $ld['please_select']?></option>
                            <?php if(isset($material_list)&&!empty($material_list)){ ?>
                                <?php foreach($material_list as $k2=>$v2){?>
                                    <option value="<?php echo $v2['OpenElement']['id']; ?>"><?php echo $v2["OpenElement"]["title"];?></option>
                                <?php }}?>
                        </select>
                        <input type="button" value="<?php echo $ld['management_of_graphic_material']; ?>" onclick="manage()"/>
                    </td>
                </tr>
                <tr id="responsenew">
                    <th><?php echo $ld['reply']?></th>
                    <td>
                        <div class="w550h162">
                            <div class="w550h40">
                                <span id="biaoqingnew" onclick="biaoqingclick()"><img src="/admin/skins/default/img/haha.png"/><?php echo $ld['expression'] ?></span>
                            </div>
                            <a class="S_func1" style="margin-left:5px;" suda-uatrack="key=tblog_new_image_upload&value=image_button" title="图片" action-data="type=508&action=1&log=image&cate=1" action-type="multiimage" href="javascript:void(0);" tabindex="3">

                                <div class="expression">
                                    <?php
                                    foreach($Expression as $k3=>$v3){
                                        echo "<div class='picks' onclick=\"pclick('new',this)\" id='[@F_".($k3+1)."@]' ><img style='margin-left:0' src='/admin/skins/default/img/gif/F_".($k3+1).".gif' title='".$v3."' /></div>";
                                    }
                                    ?>
                                    <div style="clear:both"></div>
                                </div>

                            </a>
                            <textarea class="input_detail" id="input_detailnew" ></textarea>
                        </div>
                    </td>
                </tr>
                <tr id="response1new">
                    <td></td>
                    <td class="fs12">
                       <em style="top:1px;">*</em> <?php echo $ld['openkeyword_reply_desc'] ?>
                    </td>
                </tr>
            </table>
            <div class="btnouter">
                <input type="button" class="am-btn am-btn-success" value="<?php echo $ld['d_submit']?>" id="sbnew" onclick="foo('new')"/> <input class="am-btn am-btn-success" type="reset" value="<?php echo $ld['d_reset']?>" />
            </div>
            <input type="hidden" id="hidnew" name="data[OpenKeywordAnswer][message]" value=""/>
        </div>
        <?php echo $form->end();?>
    </div>
</div>
<?php if(isset($key_list) && sizeof($key_list)>0){foreach($key_list as $k1=>$v1){?>
    <div class="am-modal am-modal-no-btn" tabindex="-1" name="answer<?php echo $v1['OpenKeywordAnswer']['id']?>" id="answer<?php echo $v1['OpenKeywordAnswer']['id']?>">
        <div class="am-modal-dialog">
            <div class="am-modal-hd"><?php echo $ld['reply'].$ld['edit']?>
                <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
            </div>
            <?php echo $form->create('OpenKeyword',array('action'=>'/viewanswer/'.$id.'/'.$v1['OpenKeywordAnswer']['id'],'name'=>"KeywordForm".$v1['OpenKeywordAnswer']['id'],'id'=>"KeywordForm".$v1['OpenKeywordAnswer']['id']));?>
            <input type="hidden" name="data[OpenKeywordAnswer][id]" value="<?php echo $v1['OpenKeywordAnswer']['id'] ?>" />
            <div class="am-modal-bd">
                <table class="am-table" style="text-align:left;">
                    <tr>
                        <th><?php echo $ld['keyword']?></th>
                        <td>
                            <input type="text" class="border" disabled="disabled" style="background:#ccc;" name="key" value="<?php echo $keyword ?>" />
                            <input type="hidden" name="data[OpenKeywordAnswer][keyword_id]" value="<?php echo $id ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['reply'].$ld['type']?></th>
                        <td>
                            <select id="seltype<?php echo $v1['OpenKeywordAnswer']['id']?>" onchange="showhide(<?php echo $v1['OpenKeywordAnswer']['id']?>)" class="border" name="data[OpenKeywordAnswer][msgtype]" value="<?php if(isset($v1['OpenKeywordAnswer']['msgtype'])){echo $v1['OpenKeywordAnswer']['msgtype'];} ?>" >
                                <option value="text" <?php if($v1['OpenKeywordAnswer']['msgtype']=="text"){echo "selected";}?>><?php echo $ld['word']; ?></option>
                                <option value="picture" <?php if($v1['OpenKeywordAnswer']['msgtype']=="picture"){echo "selected";}?>><?php echo $ld['graphics']; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr id="picresource<?php echo $v1['OpenKeywordAnswer']['id']?>" style="<?php if($v1['OpenKeywordAnswer']['msgtype']=='text'){ echo 'display:none';}?>">
                        <th><?php echo $ld['graphic_resources']; ?></th>
                        <td>
                            <select id="type<?php echo $v1['OpenKeywordAnswer']['id']?>" class="border" name="data[OpenKeywordAnswer][element_id]" >
                                <option value="00"><?php echo $ld['please_select']?></option>
                                <?php if(isset($material_list)&&!empty($material_list)){ ?>
                                    <?php foreach($material_list as $k2=>$v2){?>
                                        <option value="<?php echo $v2['OpenElement']['id']; ?>" <?php if($v2['OpenElement']['id']==$v1['OpenKeywordAnswer']['element_id']){echo "selected";}?>><?php echo $v2["OpenElement"]["title"];?></option>
                                    <?php }}?>
                            </select>
                            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['management_of_graphic_material']; ?>" onclick="manage()"/>
                        </td>
                    </tr>
                    <tr id="response<?php echo $v1['OpenKeywordAnswer']['id']?>" style="<?php if($v1['OpenKeywordAnswer']['msgtype']=='picture'){ echo 'display:none';}?>">
                        <th><?php echo $ld['reply']?></th>
                        <td>
                            <div class="w550h162">
                                <div class="w550h40">
                                    <span id="biaoqing<?php echo $v1['OpenKeywordAnswer']['id']?>" onclick="biaoqingclick()" class="face" ><img src="/admin/skins/default/img/haha.png"/><?php echo $ld['expression'] ?></span>
                                </div>
                                <a class="S_func1" style="margin-left:5px;" suda-uatrack="key=tblog_new_image_upload&value=image_button" title="图片" action-data="type=508&action=1&log=image&cate=1" action-type="multiimage" href="javascript:void(0);" tabindex="3">
                                    <div class="expression">
                                        <?php
                                        foreach($Expression as $k3=>$v3){
                                            echo "<div class='picks' onclick='pclick({$v1['OpenKeywordAnswer']['id']},this)' id='[@F_".($k3+1)."@]'><img style='margin-left:0' src='/admin/skins/default/img/gif/F_".($k3+1).".gif' title='".$v3."' /></div>";
                                        }
                                        ?>
                                        <div style="clear:both"></div>
                                    </div>
                                </a>
                                <textarea class="input_detail" id="input_detail<?php echo $v1['OpenKeywordAnswer']['id']?>" ><?php if(isset($v1['OpenKeywordAnswer']['message'])){echo $v1['OpenKeywordAnswer']['message'];}?></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr id="response1<?php echo $v1['OpenKeywordAnswer']['id']?>" style="<?php if($v1['OpenKeywordAnswer']['msgtype']=='picture'){ echo 'display:none';}?>">
                        <td></td>
                        <td class="fs12">
                            <em style="top:1px;">*</em><?php echo $ld['openkeyword_reply_desc'] ?>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" id="sb<?php echo $v1['OpenKeywordAnswer']['id']?>" onclick="foo(<?php echo $v1['OpenKeywordAnswer']['id']?>)"/> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
                <input type="hidden" id="hid<?php echo $v1['OpenKeywordAnswer']['id']?>" name="data[OpenKeywordAnswer][message]" value=""/>
            </div>
            <?php echo $form->end();?>
        </div>
    </div>
<?php }} ?>
<script>
    var keywordflag=false;
    var keywordmsg="";
    function check_page_style(){
        var name=document.getElementsByName("data[OpenKeyword][keyword]")[0].value;
        if(name==""){
            keywordmsg="<?php printf($ld['name_not_be_empty'],$ld['keyword']); ?>";
            alert(keywordmsg);
            keywordflag=false;
            return false;
        }else{
            keywordflag=true;
        }
        if(keywordflag){
            document.getElementById("KeywordFormview").submit();
        }
    }

    function distinctkw(input,id){
        var keyword=input.value;
        if(keyword==""){
            keywordmsg="<?php printf($ld['name_not_be_empty'],$ld['keyword']); ?>";
            alert(keywordmsg);
            keywordflag=false;
            return;
        }
        var func="open_keywords/distinctkeyword";
        var sUrl = admin_webroot+func;//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {keyword:keyword,id:id},
            success: function (result) {
                if(result.flag == 1){
                    keywordmsg="<?php echo $ld['keyword_not_repeated'] ?>";
                    alert(keywordmsg);
                    keywordflag=false;
                }else{
                    keywordflag=true;
                    keywordmsg="";
                }
            }
        });
    }

    var flag=false;
    function check_page_style1(obj){
        var h=$("#hid"+obj).val();
        var p=$("#type"+obj).val();
        if(h==""){
            if(p=="00"){
                alert("<?php printf($ld['name_not_be_empty'],$ld['reply']); ?>");
                flag=false;
            }else{
                flag=true;
            }
        }else{
            flag=true;
            p="00";
        }
        if(flag){
            $("#KeywordForm"+obj).submit();
            flag=false;
        }
    }

    var Url="<?php echo $server_host; ?>/admin/img/gif/";//表情图片路径
    //表情数组
    var Expression=new Array("/微笑","/撇嘴","/好色","/发呆","/得意","/流泪","/害羞","/睡觉","/尴尬","/呲牙","/惊讶","/冷汗","/抓狂","/偷笑","/可爱","/傲慢","/犯困","/流汗","/大兵","/咒骂","/折磨/","/衰","/擦汗","/抠鼻","/鼓掌","/坏笑","/左哼哼","/右哼哼","/鄙视","/委屈","/阴险","/亲亲","/可怜","/爱情","/飞吻","/怄火","/回头","/献吻","/左太极");

    //多次替换
    String.prototype.replaceAll = function (findText, repText){
        var newRegExp = new RegExp(findText, 'gm');
        return this.replace(newRegExp, repText);
    }

    //表情文字替换
    function replace_content(con){
        for(var i=0;i<Expression.length;i++){
            con = con.replaceAll(Expression[i],"<img src='" + Url + "F_"+(i+1)+".gif' />");
        }
        return con;
    }
    var clicks = true;

    function biaoqingclick(){
        if($(".expression").css("display")=="block"){
            $(".expression").css("display","none");
        }else{
            $(".expression").css("display","block");
            clicks=false;
        }
    }
    document.body.onclick = function(){
        if(clicks){
            $(".expression").css("display","none");
        }
        clicks = true;
    }

    //表情点击后的事件
    function pclick(id,obj){
        var titles=$(obj).children().attr("title");
        var ids=$(obj).attr("id");
        if($("#input_detail"+id).val()==""){
            $("#input_detail"+id).val(titles);
            $("#input_detaill"+id).val(titles);
        }else{
            $("#input_detail"+id).val($("#input_detail"+id).val()+titles);
            $("#input_detaill"+id).val($("#input_detaill"+id).val()+titles);
        }
    }

    function foo(obj){
        if(obj=="new"){
            $("#sbnew").attr('disabled', 'disabled');
        }
        var con=$("#input_detail"+obj).val();
        $("#hid"+obj).val(con);
        check_page_style1(obj);
    }

    function showhide(obj){
        if($("#seltype"+obj).val()=="text"){
            $("#response"+obj).show();
            $("#response1"+obj).show();
            $("#picresource"+obj).hide();
        }else{
            $("#response"+obj).hide();
            $("#response1"+obj).hide();
            $("#hid"+obj).val("");
            $("textarea"+obj).val("");
            $("#picresource"+obj).show();
        }
    }

    function manage(){
        window.location.href="/admin/open_elements";
    }

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
            if(confirm("<?php echo $ld['confirm_delete'] ?>"))
            {
                document.OpenCallKeywordForm.action=admin_webroot+"open_keywords/removeanswer/";
                document.OpenCallKeywordForm.onsubmit= "";
                document.OpenCallKeywordForm.submit();
            }
        }
    }

    function remove1(id){
        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
            var func="open_keywords/removeanswer";
            var sUrl = admin_webroot+func;//访问的URL地址
            $.ajax({
                type: "POST",
                url:sUrl,
                dataType: 'json',
                data: {id:id},
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

    function select_imge(id_str,obj){
        window.open(admin_webroot+'/image_spaces/select_image/'+id_str+"/", 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
    }
</script>