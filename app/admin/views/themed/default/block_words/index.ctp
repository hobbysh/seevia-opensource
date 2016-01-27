<style>
.am-form-label{margin-left:20px;}	
</style>

<div class="listsearch">
    <?php
    echo $form->create('block_words',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-inline am-form-horizontal'));
    ?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" >
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-2 am-u-md-2 am-u-sm-2   am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-lg-8 am-u-md-7 am-u-sm-7 am-text-left" >
                <input placeholder="<?php echo $ld['keyword'];?>" type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:"";?>" />
            </div>
           <div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-sm-only">
	           	  <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search']?>" />
	    </div>
          </li>
           <li style="margin:0 0 10px 0" class="am-show-sm-only">
              <label class="am-u-lg-1 am-u-md-1 am-u-sm-2   am-form-label"></label>
              <div class="am-u-lg-2 am-u-md-7 am-u-sm-7">
	           	  <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search']?>" />
	          </div>
        </li>
    </ul>
    <?php
    echo $form->end();
    ?>
</div>
<p class="am-u-md-12 am-btn-group-xs" style="margin-bottom:10px;">
    
    					<a class="am-btn am-btn-warning am-radius am-btn-sm am-fr" href="<?php echo $html->url('/block_words/view/0'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['add'].$ld['keyword'] ?>
				    </a>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <?php
    echo $form->create('block_words',array('action'=>'/removeAll','name'=>'BlogForm','type'=>'get',"onsubmit"=>"return false;"));
    ?>
    <table class="am-table  table-main">
    	<thead>
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b> <?php echo $ld['type'];?></b></label></th>
            <th></th>
            <th><?php echo $ld['keyword'];?></th>
            <th >创建时间</th>
            <th style="width:200px;"><?php echo $ld['operate'];?></th>
        <tr>
        </thead>
            <?php
            if(isset($wordsinfo)&&count($wordsinfo)>0){
            foreach($wordsinfo as $k=>$v){
             
            ?>
        <tr>
            <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['BlockWord']['id'];?>" /></span><?php echo $v["BlockWord"]["type"]==0?$ld['filter']:$ld['replace']; ?></td>
            <td></td>
            <td><?php echo $v["BlockWord"]["word"]; ?></td>
            <td><?php echo $v["BlockWord"]["created"]; ?></td>
            <td>
                <a class="am-btn am-btn-success am-btn-xs am-radius" href='/admin/block_words/<?php echo $v['BlockWord']['id']; ?>'><span class="am-icon-eye"></span> <?php echo $ld['view'];?></a>
                 <?php?>
                 <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'block_words/remove/<?php echo $v['BlockWord']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                <?php ?>
            </td>
        <tr>
            <?php
            }
            }else{
            ?>
        <tr>
            <td colspan="5"class="no_data_found"><?php echo $ld['no_data_found']?></td>
        </tr>
        <?php
        }
        ?>
    </table>
     <?php if(isset($wordsinfo) && sizeof($wordsinfo)){ ?>
		<div id="btnouterlist" class="btnouterlist"style="height:45px;">
           <div class=" am-u-lg-3 am-u-md-4 am-u-sm-12 am-hide-sm-only"style="margin-left:1px;">
	            <label style="margin-right:5px;float:left;margin-top:6px;" class="am-checkbox am-success"> <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></label> 
	            <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['batch_delete']?>"  onclick="removeAll()"> 
           </div>
           <div class="am-u-lg-9 am-u-md-7 am-u-sm-12"><?php echo $this->element('pagers');?></div>
           <div class="am-cf"></div>
        </div>
	<?php }?>
    <?php echo $form->end(); ?>
</div>
<script type="text/javascript">
    function formsubmit()
    {
        var keyword=document.getElementById("keyword").value;
        var url="keyword="+keyword;
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
            if(confirm('确认删除？'))
            {
                batch_action()
            }
        }
    }

    function batch_action()
    {
        document.BlogForm.action=admin_webroot+"block_words/removeAll";
        document.BlogForm.onsubmit= "";
        document.BlogForm.submit();
    }
</script>