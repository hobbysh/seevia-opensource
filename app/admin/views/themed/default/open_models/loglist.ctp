<style>.am-form-label{font-weight:bold; margin-top:-4px; margin-left:17px;}</style>
<div class="listsearch">
    <?php echo $form->create('OpenModel',array('action'=>'/loglist/'.$open_model_info['OpenModel']['id'],'name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-2  am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-lg-8  am-u-md-7 am-u-sm-7" style="padding:0 0.5rem;">
                <input type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-2  am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['added_time'];?></label>
            <div class="am-u-lg-3  am-u-md-3 am-u-sm-3" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="<?php echo isset($start_date)?$start_date:'';?>" />
            </div>
            <em class="am-u-lg-1  am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;">-</em>
            <div class="am-u-lg-3  am-u-md-3 am-u-sm-3" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="<?php echo isset($end_date)?$end_date:'';?>" />
            </div>
              <div style="padding:0 0.5rem;" class="am-show-lg-only">
                  <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/> 
            </div>	
        </li>
        <li style="margin:0 0 10px 0"class="am-hide-lg-only">
        	 <label class="am-u-lg-2  am-u-md-2 am-u-sm-3 am-form-label"> </label>
            <div class="am-u-lg-8  am-u-md-7 am-u-sm-7" style="padding:0 0.5rem;">
                  <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/>
              </div>
         </li>
    </ul>
    <?php echo $form->end()?>
</div>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th><?php echo "用户"; ?></th>
            <th class="thwrap am-hide-md-down"><?php echo "发送对象"; ?></th>
            <th><?php echo "消息类型"; ?></th>
            <th class="thwrap am-hide-md-down"><?php echo "消息内容"; ?></th>
            <th class="thwrap am-hide-sm-down"><?php echo "发送时间"; ?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($loglist)&&sizeof($loglist)>0){ foreach($loglist as $k=>$v){ ?>
            <tr>
                <td><?php echo urldecode($v['OpenUser']['nickname']);?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenUserMessage']['send_from']=="1"?"用户":$ld['system']; ?></td>
                <td ><?php echo $v['OpenUserMessage']['msgtype']?></td>
                <td class="thwrap am-hide-md-down" style="width:400px;"><div class="ellipsis" style="text-align:left;width:400px;"><?php echo substr($v['OpenUserMessage']['message'],0,200) ?></div></td>
                <td  class="thwrap am-hide-sm-down"><?php echo $v['OpenUserMessage']['created']?></td>
                <td><a target="_blank" class="am-btn am-btn-success am-btn-xs am-radius" href="/admin/open_models/log_view/<?php echo $v['OpenUserMessage']['id']; ?>/<?php echo $open_model_info['OpenModel']['id'] ?>">查看</td>
            </tr>
        <?php }}else{ ?>
            <tr>
                <td colspan="6">暂时没有记录</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div id="btnouterlist" class="btnouterlist"><?php echo $this->element('pagers')?></div>
</div>
<style>
    .ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: inherit;
        white-space: nowrap;
        width: 300px;
    }
</style>