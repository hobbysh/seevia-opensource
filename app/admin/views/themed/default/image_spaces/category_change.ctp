<?php
/*****************************************************************************
 * SV-Cart 底部
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
echo $javascript->link('/skins/default/js/image_space');?>
<input type="hidden" id="session_id" value="<?php echo $admin['id'];?>">
<p class="am-u-md-12"><?php echo $html->link($ld['picture_list'],"/image_spaces/",array("class"=>"am-btn am-btn-sm am-fr"),false,false);?></p>
<?php echo $form->create('image_spaces',array('action'=>'category_change/'.(isset($image_info["PhotoCategoryGallery"]["id"])?$image_info["PhotoCategoryGallery"]["id"]:""),'name'=>'theForm'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['transfer_category']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="tablemain" class="tablemain am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['transfer_category']?></h4>
        </div>
        <div id="transfer_category" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table id="t1" class="am-table">
                    <tr><th><?php echo $ld['image_category']?></th><td><select style="width:200px;" id="photos_cat_id" onchange="swf_upload_addr()" disabled="true">
                                <option value="0"><?php echo $ld['not_in_category']?></option>
                                <?php foreach( $photo_category_data as $k=>$v ){?>
                                    <option value="<?php echo $v['PhotoCategory']['id'];?>" <?php if($image_info["PhotoCategoryGallery"]["photo_category_id"] == $v['PhotoCategory']['id']){echo "selected";}?> ><?php echo $v['PhotoCategoryI18n']['name'];?></option>
                                <?php }?>
                            </select>
                            <input type="hidden" id="img_id" value='<?php echo $image_info["PhotoCategoryGallery"]["id"]?>'/>
                        </td></tr>
                    <tr><th><?php echo $ld['transfer_photos_category']?></th><td>
                        <select style="width:200px;" id="photo_category_id" name="data[PhotoCategory][id]">
                            <option value="0"><?php echo $ld['select_a_category']?></option>
                            <?php foreach($photo_category_data as $k=>$v){?>
                                <option value='<?php echo $v["PhotoCategory"]["id"];?>' <?php if($image_info["PhotoCategoryGallery"]["photo_category_id"]==$v["PhotoCategory"]["id"]){echo "selected";}?>><?php echo $v["PhotoCategoryI18n"]["name"];?></option>
                            <?php }?>
                        </select>
                </table>
                <div class="btnouter">
                	<input class="am-btn am-btn-success am-btn-sm" type="submit" value="<?php echo $ld['d_submit']?>" />  <input type="reset" class="am-btn am-btn-success am-btn-sm" value="<?php echo $ld['d_reset']?>" />
            	</div>
			</div>
        </div>
    </div>
</div>
<?php echo $form->end();?>