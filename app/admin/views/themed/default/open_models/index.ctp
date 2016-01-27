<style>.am-form-label{font-weight:bold; margin-top:-4px; margin-left:17px;}</style>
<div class="listsearch">
    <?php echo $form->create('OpenModels',array('action'=>'/','id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-2  am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-lg-7  am-u-md-7 am-u-sm-4 " style="padding:0 0.5rem;">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
              <div class="am-hide-sm-only">
            <input style="margin-left:5px;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>" />      </div>  
        </li>
            <li  style="margin:10px 0 0 0" class="am-show-sm-only">
             <label class="am-u-lg-2  am-u-md-2 am-u-sm-2 am-form-label"></label>
            <div class="am-u-lg-7  am-u-md-7 am-u-sm-4 " style="padding:0 0.5rem;">
                 <input style="margin-left:5px;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>" /> 
            </div>
        </li>
    
    </ul>
    <?php echo $form->end();?>
</div>
<?php if($svshow->operator_privilege("open_models_add")){?>
<p class="am-u-md-12 am-btn-group-xs">
    
	<a class="am-btn am-btn-warning am-radius am-btn-sm  am-fr" href="<?php echo $html->url('/open_models/view'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['add'] ?>
				</a>
	</p>
<?php }?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
    	<thead>
            <tr>
                
                <th><?php echo $ld['open_model_account'];?></th>
                <th class="am-hide-md-down"><?php echo $ld['open_model'];?></th>
                <th class="am-hide-md-down"><?php echo 'AppId';?></th>
                <th class="am-hide-md-down"><?php echo $ld['open_model'].$ld['description'];?></th>
    		   <th ><?php echo $ld['status']?></th>
                <th><?php echo $ld['operate']?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($model_list) && sizeof($model_list)>0){foreach($model_list as $k=>$v){?>
            <tr>
                
                <td><?php echo $v['OpenModel']['open_type_id']?></td>
                <td class="thwrap am-hide-md-down"><?php if($v['OpenModel']['open_type'] == 'wechat'){
			               	echo $ld['wechat'];
			              }else{
			              	echo $v['OpenModel']['open_type'];
			              }?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenModel']['app_id']?></td>
                <td class="thwrap am-hide-md-down"><div class="ellipsis"><?php echo $v['OpenModel']['content']?></div></td>
                <td><?php if($svshow->operator_privilege('products_edit')){
                			if($v['OpenModel']['status']){
                				echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"open_models/toggle_on_status",'.$v["OpenModel"]["id"].')></div>';
                			}else{
                				echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"open_models/toggle_on_status",'.$v["OpenModel"]["id"].')></div>';
                			}
                		}else{
                			if($v['OpenModel']['status']){
                				echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                			}else{
                				echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                			}
                		} ?>
                </td>
                <td class="am-action"><?php
                    if($svshow->operator_privilege("open_models_edit")){?>
                    
                           <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/open_models/view/'.$v['OpenModel']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                   <?php  }
                    if($svshow->operator_privilege("open_models_remove")){?>
                           <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'/open_models/remove/<?php echo $v['OpenModel']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                   <?php  }
                    if($svshow->operator_privilege("open_models_remove")){
                    	echo $html->link($ld['log_platform'],"/open_models/loglist/{$v['OpenModel']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs am-radius"));
                    }
                ?></td>
            </tr>
            <?php }}else{?>
            <tr>
                <td colspan="10" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<style>
.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: capitalize;
    white-space: nowrap;
    width: 150px;
}
</style>