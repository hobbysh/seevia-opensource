<style>.am-form-label{font-weight:bold;  margin-top:-4px;margin-left:17px; }</style>
<div class="listsearch">
    <?php echo $form->create('OpenUsers',array('action'=>'/','id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class=" am-u-lg-2  am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['type']; ?></label>
            <div class="am-u-lg-8  am-u-md-8 am-u-sm-7 am-u-end" style="padding:0 0.5rem;">
                <select id='OpenModelType' name='openType' data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value=' ' ><?php echo $ld['all_data']?> </option>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3  am-u-md-3 am-u-sm-4  am-form-label"><?php echo $ld['open_model_account']; ?></label>
            <div class="am-u-lg-8  am-u-md-8 am-u-sm-7 " style="padding:0 0.5rem;">
                <select name="open_type_id" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value=""><?php echo $ld['all_data'] ?></option>
                    <?php foreach($open_type as $k=>$v){ ?>
                        <option value="<?php echo $v['OpenModel']['open_type_id'] ?>" <?php echo isset($open_type_id)&&$open_type_id==$v['OpenModel']['open_type_id']?'selected':'' ?>><?php echo $v['OpenModel']['open_type_id'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
                <li style="margin:0 0 10px 0">
        			 <label class="am-u-lg-2  am-u-md-2 am-u-sm-4  am-form-label"><?php echo $ld['keyword']?></label>
		            <div class="am-u-lg-8  am-u-md-8 am-u-sm-7" style="padding:0 0.5rem;">
		                <input placeholder="<?php echo $ld['nickname'];?>/<?php echo 'OpenId';?>" type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
		            </div>
        	</li>
        <li style="margin:0 0 10px 0">
           <label class="am-u-lg-2  am-u-md-3 am-u-sm-4  am-form-label"><?php echo $ld['status']?></label>
            <div class="am-u-lg-8  am-u-md-8 am-u-sm-7 " style="padding:0 0.5rem;">
                <select id='OpenUserStatus' name='subscribe' data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value=""><?php echo $ld['all_data'] ?></option>
                    <option <?php if (isset($subscribe) && $subscribe == '1') echo 'selected'; ?> value="1"><?php echo $ld['attention'] ?></option>
                    <option <?php if (isset($subscribe) && $subscribe == '0') echo 'selected'; ?> value="0"><?php echo $ld['cancel_attention'] ?></option>
                </select>
            </div>
        </li>
         
        <li style="margin:0 0 10px 0;" >
          <label class="am-u-lg-3  am-u-md-2 am-u-sm-4  am-form-label"> </label>
            <div class="am-u-lg-8  am-u-md-8 am-u-sm-7 " style="padding:0 0.5rem;">
            <input style="margin-right:10px;" class="am-btn am-btn-success am-radius am-btn-xs  " type="submit" value="<?php echo $ld['search'];?>" />
        	</div>
        </li>
        	
    </ul>
    <?php echo $form->end();?>
</div>
<?php if(isset($open_type)&&!empty($open_type)){ ?>
    <?php if($svshow->operator_privilege("open_users_view")){ ?>
             <div class="am-fr "> 
             	 <div class="am-u-sm-5 am-u-md-5 am-u-lg-5 am-u-end am-fr">
                      <a  class="am-btn am-btn-warning am-radius am-btn-sm " href="javascript:void(0);" onclick="get_api_user()"><?php echo $ld['update'].$ld['list'] ?>
	            </a>
	             </div>
	            	<div class="am-u-sm-7 am-u-md-7 am-u-lg-7 am-fr" >
	            <select  id="open_type_list" style="float:right;margin:5px;"data-am-selected>
	                <option value="0"><?php echo $ld['please_select'].$ld['open_model_account'] ?></option>
	                <?php foreach($open_type as $k=>$v){ ?>
	                    <option value="<?php echo $v['OpenModel']['id'] ?>"><?php echo $v['OpenModel']['open_type_id'] ?></option>
	                <?php } ?>
	            </select>
	            	</div>    
	           
	     
	             
       </div>
    <?php }?>
<?php }?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="t1" class="am-table  table-main">
        <thead>
        <tr>
            <th><?php echo $ld['avatar'];?></th>
            <th><?php echo $ld['open_model'];?></th>
            <th><?php echo $ld['open_model_account'];?></th>
            <th class="thwrap am-hide-md-down"><?php echo 'OpenId';?></th>
            <th><?php echo $ld['nickname'];?></th>
            <th><?php echo $ld['gender'];?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['language'];?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['city'];?></th>
            <th><?php echo $ld['province'];?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['country'];?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['update'].$ld['time'];?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['status'];?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($user_list) && sizeof($user_list)>0){foreach($user_list as $k=>$v){?>
            <tr>
                <td><img style="width:60px;height:60px;" src="<?php echo $v['OpenUser']['headimgurl']?>"></td>
                <td><?php echo ($v['OpenUser']['open_type'] == 'wechat')?$ld['wechat']: $v['OpenModel']['open_type'];?></td>
                <td><?php echo $v['OpenUser']['open_type_id']?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenUser']['openid']?></td>
                <td><?php echo urldecode($v['OpenUser']['nickname']);?></td>
                <td><?php echo ($v['OpenUser']['sex']==1)?$ld['male']:$ld['female'] ?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenUser']['language']?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenUser']['city']?></td>
                <td><?php echo $v['OpenUser']['province']?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenUser']['country']?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenUser']['created']?></td>
                <td class="thwrap am-hide-md-down"><?php echo ($v['OpenUser']['subscribe']==1)?$ld['attention']:$ld['cancel_attention']?></td>
                <td><?php
                    if($svshow->operator_privilege("open_users_view")){
                        echo $html->link($ld['view'],"/open_users/view/{$v['OpenUser']['id']}",array("class"=>"am-btn am-btn-success am-seevia-btn am-btn-xs am-radius"));
                        
                    }
                    ?></td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="13" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($user_list) && sizeof($user_list)){?>
    <div id="btnouterlist" class="btnouterlist">
        <?php echo $this->element('pagers')?>
    </div>
    <?php } ?>
</div>
<script>
    function get_api_user(){
        var open_type=document.getElementById('open_type_list').value;
        if(open_type!='0'){
            window.location.href="/admin/open_users/api_user_action/"+open_type;
        }else{
            alert("<?php echo $ld['please_select'].$ld['open_model_account'] ?>");
            return false;
        }
    }

    function batch_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var postData = "";
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                postData+="&checkboxes[]="+bratch_operat_check[i].value;
            }
        }
        if( postData=="" ){
            alert("<?php echo $ld['please_select'] ?>");
            return;
        }
    }
</script>