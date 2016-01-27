<style>.am-form-label{font-weight:bold;  margin-top:4px;margin-left:13px; }</style>
<?php echo $form->create('Route',array('action'=>'/','name'=>'ArticleForm','type'=>'get',"onsubmit"=>"return false;"));?>
<div id="tablelist"  >
	
         <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
		                 <li  style="margin:0 0 10px 0">
			                  <label class="am-u-lg-2 am-u-md-2  am-u-sm-3  am-form-label"><?php echo $ld['keyword'];?></label>
				            <div class="am-u-lg-8 am-u-md-8  am-u-sm-7 ">
				            <input style=" height:33px;" placeholder="<?php echo $ld['controller']?>/<?php echo $ld['method']?>/<?php echo $ld['url']?>" type="text" name="route_keywords" id="route_keywords" value="<?php echo @$route_keywords;?>" onkeypress="sv_search_action_onkeypress(this,event)" />
				           </div>
			                       <div class="am-u-lg-1  am-u-md-1 am-u-sm-3 am-hide-sm-only">    
	                               <input type="submit"  class="am-btn am-btn-success am-radius am-btn-sm  " value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
	                             </div> 
	                      </li>
	                      	  <li style="margin:0 0 10px 0" class="am-show-sm-only">
	                      	   <label class="am-u-lg-2 am-u-md-2  am-u-sm-3  am-form-label"> </label>
	                           <div class="am-u-lg-1  am-u-md-1 am-u-sm-3">    
	                               <input type="submit"  class="am-btn am-btn-success am-radius am-btn-sm  " value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
	                             </div> 
	                   		  </li>  
		 </ul> 	
		 <p class="am-u-md-12 am-u-lg-12 am-text-right am-btn-group-xs">
		    <?php
		    if($svshow->operator_privilege("articles_add")){?>
		         <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view'); ?>"> <span class="am-icon-plus"></span> <?php echo $ld['add_route'] ?>  </a>
		  <?php   }?>
		</p>
 	
<div class="am-u-md-12 am-u-sm-12  am-u-lg-12 ">
    <table class="am-table  table-main">
        <thead>
        <tr> 	
    <th > <label style="margin:0 5px 0 0; font-weight:bold; " class="am-checkbox am-success"><span class="am-hide-sm-down "  ><input   type="checkbox"  onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck/></span><?php echo $ld['url']; ?> </label>
    </th>
             <th  ><?php echo $ld['controller']?></th>
            <th class="am-hide-md-down"><?php echo $ld['method']?></th>
            <th class="am-hide-md-down"><?php echo $ld['modelID']?></th>
            <th class="am-hide-md-down"><?php echo $ld['option']?></th>
            <th><?php echo $ld['status']?></th>
            <th style="width:260px;"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
 
        <?php if(isset($routes) && sizeof($routes)>0){foreach($routes as $k=>$v){?>
            <tr>
                <td><div><label style="margin:0 20px 0 0;" class="am-checkbox am-success"><label class="am-hide-sm-down" ><input  type="checkbox" name="checkboxes[]" data-am-ucheck/ value="<?php echo $v['Route']['id']?>" /></label><span onclick="javascript:listTable.edit(this, 'timer/update_cronjob_status/', <?php echo $v['Route']['id'] ?>)"> <?php echo $v['Route']['url'] ?></span></label></div></td>
                
                <td>
                    <?php echo $v['Route']['controller'] ?>
                </td>
                <td class="am-hide-md-down">
                    <?php echo $v['Route']['action'] ?>
                </td>
                <td class="am-hide-md-down">
                    <?php echo $v['Route']['model_id'] ?>
                </td>
                <td class="am-hide-md-down">
                    <?php echo $v['Route']['options'] ?>
                </td>
                <td><?php if ($v['Route']['status'] == 1){?>
                		 <div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick="listTable.toggle(this,'routes/toggle_on_status','<?php echo $v['Route']['id']?>')"></div> 
                    <?php }elseif($v['Route']['status'] == 0){?>
                    		 
                    	<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick="listTable.toggle(this,'routes/toggle_on_status','<?php echo $v['Route']['id']?>')"></div>
                    		
                    <?php }?>
                </td>
                <td class="am-btn-group-xs am-action">
                    <?php {?>
                    <?php //echo $v['Route']['url']; ?>
                    <a class="am-btn am-btn-success am-btn-xs am-seevia-btn " target='_blank' href="<?php echo $server_host.'/'.$v['Route']['url']; ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                    </a>
                   <?php } ?>
                    <?php if($svshow->operator_privilege("routes_edit")){?>
                         <a class="am-btn am-btn-default  am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/routes/view/'.$v['Route']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                   <?php  }
                    if($svshow->operator_privilege("routes_remove"))
                    {?>
                         
<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'routes/remove/<?php echo $v['Route']['id'] ?>');">
<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
</a>
                  <?php   }
                    ?>
                </td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="8" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($routes) && sizeof($routes)){?>
        <div id="btnouterlist" class="btnouterlist">
            <?php if($svshow->operator_privilege("products_batch")){?>
                <div class="am-u-lg-6 am-u-md-12 am-u-sm-12 am-hide-sm-down">
                    <label style="margin:5px 5px 5px 0px;float:left;" class="am-checkbox am-success">
                    <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/ ><?php echo $ld['select_all']?></label>
                    <input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['delete']?>" onclick="diachange()" />
                </div>
            <?php }?>
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers');?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
		

    <?php echo $form->end();?>	
<script type="text/javascript">
    function formsubmit(){
        var product_keywords=document.getElementById('route_keywords').value;
        var ta = checkbox();
        var url = "route_keywords="+product_keywords;
        window.location.href = encodeURI(admin_webroot+"routes?"+url);
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

    function diachange(){
        var id=document.getElementsByName('checkboxes[]');
        var i;
        var j=0;
        var image="";
        for( i=0;i<=parseInt(id.length)-1;i++ ){
            if(id[i].checked){
                j++;
            }
        }
        if( j>=1 ){
            if(confirm("<?php echo $ld['confirm_delete']?>"))
            {
                batch_action();
            }
        }else{
            if(confirm(j_please_select))
            {
                return false;
            }
        }
    }

    function batch_action()
    {
        document.ArticleForm.action=admin_webroot+"routes/batch";
        document.ArticleForm.onsubmit= "";
        document.ArticleForm.submit();
    }
</script>
