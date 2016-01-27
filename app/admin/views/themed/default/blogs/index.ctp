<style type="text/css">
    .ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: capitalize;
        white-space: nowrap;
        width:300px;
    }
    .am-form-label{font-weight:bold; text-align:center; margin-top:-5px;margin-left:20px;}
    td>img{width:40px;}
</style>
<div class="listsearch">
    <?php
    echo $form->create('Blogs',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-inline am-form-horizontal'));
    ?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-lg-3  am-u-md-3  am-form-label"><?php echo $ld['user_name'] ?></label>
            <div class="am-u-sm-7 am-u-lg-7 am-u-md-7" style="padding:0 0.5rem;">
                <input type="text" name="keyword" id="blog_keyword" value="<?php echo isset($keyword)?$keyword:"";?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-lg-3  am-u-md-3 am-form-label"><?php echo $ld['create_time'] ?></label>
            <div class="am-u-sm-3 am-u-lg-3  am-u-md-3   " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
            </div>
            <em class="am-fl am-u-sm-1  am-u-lg-1  am-u-md-1 am-text-center" style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3 am-u-lg-3  am-u-md-3  am-u-end " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
            </div>
            	<div class=" am-u-lg-1 am-show-lg-only">
        		<input class="am-btn am-btn-success am-radius am-btn-sm " type="submit" value="<?php echo $ld['search'];?>"/>
        		</div>	
        </li>
        <li style="margin:0 0 10px 0"class="am-hide-lg-only">
        		 <label class="am-u-sm-3  am-u-lg-1  am-u-md-3  am-form-label"></label>
        		<div class="am-u-sm-2  am-u-lg-2  am-u-md-2">
        		<input class="am-btn am-btn-success am-radius am-btn-sm " type="submit" value="<?php echo $ld['search'];?>"/>
        		</div>
        	</li>
    </ul>
    <input type="hidden" name="blogId" value='<?php echo isset($blogId)?$blogId:""; ?>' />
    <?php
    echo $form->end();
    ?>
</div>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <?php
    echo $form->create('Blog',array('action'=>'/removeAll','name'=>'BlogForm','type'=>'get',"onsubmit"=>"return false;"));
    ?>
    <input type="hidden" name="deltype" value="index" />
    <table id="t1" class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap  "> 
        <label style="margin:0 0 0 0; font-weight:bold" class="am-checkbox  am-success "><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span> <?php echo $ld['user_name'] ?></label>
        </th>
          
            <th class=""><?php echo $ld['comment_object'] ?></th>
            <th class="ellipsis  am-hide-sm-down"><?php echo $ld['blog_content']; ?></th>
            <th class="thwrap am-hide-sm-down" width="150px"><?php echo $ld['create_time'] ?></th>
            <th width="150px"><?php echo $ld['operate'] ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($bloginfo) && !empty($bloginfo)){
            foreach($bloginfo as $k){
                ?>
                <tr>
                    <td class="thwrap  ">
                    	<label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $k['Blog']['id']?>" /></span>
                    <?php
                        	echo isset($k["Blog"]["user_name"])?$k["Blog"]["user_name"]:"<font color='red'>该用户已不存在</font>";
                        ?>
                    </td>
                    
                    <td >
                        <?php echo isset($k["Blog"]["parentinfo"])?$k["Blog"]["parentinfo"]:"<font color='red'>已删除！</font>"; ?>
                    </td>
                    <td class="ellipsis  am-hide-sm-only"><?php echo $k["Blog"]["content"]; ?>
                    </td>
                    <td class="thwrap am-hide-sm-only "><?php echo $k["Blog"]["created"]; ?>
                    </td>
                    <td> <?php if($svshow->operator_privilege("showblogs_reomve")){
                            echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-xs am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){list_delete_submit('{$admin_webroot}Blogs/remove/{$k['Blog']['id']}');}"));
                        }?>
                   </td>
                </tr>
            <?php
            }
        }else{
            ?>
            <tr>
                <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <?php if(isset($bloginfo) && !empty($bloginfo)){ ?>
    <div id="btnouterlist" class="btnouterlist">
        <?php if($svshow->operator_privilege("showblogs_reomve")){?>
            <div class="am-u-lg-6 am-u-md-4 am-u-sm-12  am-hide-sm-down">
                <label style="margin-right:5px;float:left;margin-top:6px;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
                    <b><?php echo $ld['select_all'] ?></b>
                </label>
                <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" onclick="removeAll()" value="<?php echo $ld['batch_delete'] ?>" />
            </div>
        <?php }?>
            <div class="am-u-lg-6 am-u-md-7 am-u-sm-12"><?php
                //打印分页信息
                echo $this->element('pagers');?>
            </div>
            <div class="am-cf"></div>
    </div>
    <?php }echo $form->end(); ?>
</div>
<script type="text/javascript">
    function formsubmit()
    {
        var keyword=document.getElementById("blog_keyword").value;
        var start_date_time=document.getElementByName("start_date_time")[0].value;
        var end_date_time=document.getElementByName("end_date_time")[0].value;
        var blogId=document.getElementByName("blogId")[0].value;
        var url="keyword="+keyword+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&blogId="+blogId;
    }

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
                batch_action()
            }
        }
    }

    function batch_action()
    {
        document.BlogForm.action=admin_webroot+"Blogs/removeAll";
        document.BlogForm.onsubmit= "";
        document.BlogForm.submit();
    }
</script>