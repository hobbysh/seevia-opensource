//扩展分类
function addOtherCat(){
     var sel = document.createElement("SELECT");
      var selCat = document.getElementById('ArticlesCategory');

      for (i = 0; i < selCat.length; i++)
      {
          var opt = document.createElement("OPTION");
          opt.text = selCat.options[i].text;
          opt.value = selCat.options[i].value;
          if (!!(window.attachEvent && navigator.userAgent.indexOf('Opera') === -1) )
          {
              sel.add(opt);
          }
          else
          {
              sel.appendChild(opt);
          }
      }
      var conObj=document.getElementById('other_cats');
      conObj.appendChild(sel);
      sel.name = "article_categories_id[]";
      sel.onChange = function() {checkIsLeaf(this);};
}
/**
  * 商品详细页关联商品，搜索商品////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  */
function searchProducts(){
	var category_id = document.getElementById("category_id");//商品分类
	var brand_id = document.getElementById("brand_id");//商品品牌
	var product_keyword = document.getElementById("product_keyword");//搜索关键字
	var min_price = document.getElementById("min_price");//最小价格
	var max_price = document.getElementById("max_price");//最大价格
	$.ajax({
		url:admin_webroot+"products/searchProducts/",
		type:"POST",
		dataType:"json",
	data:{category_id:category_id.value,brand_id:brand_id.value,min_price:min_price.value,max_price:max_price.value,product_keyword:product_keyword.value},
		success:function(data){
			if(data.flag=="1"){
					var product_select_sel = document.getElementById('product_selectds');
					//alert(product_select_sel);
					$(product_select_sel).html();
					if(data.content){
					  	var selhtml=""; 
						for(i=0;i<data.content.length;i++){
							selhtml+= "<tr><td ><div class='am-checkbox am-fl' style='margin-left:10px; '><label><input type='checkbox' name='checkboxes[]'  value="+data.content[i]['Product'].id+" data-am-ucheck /></label> </div> <div>"+data.content[i]['Product'].code+"--"+data.content[i]['ProductI18n'].name+"</div></td></tr>";
						}
						 
					
						$(product_select_sel).html("<table>"+selhtml+"</table>");
							//alert(selhtml);
			         }
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
	}
	});
		
}
////////////////////////// 商品 添加
//编辑页 关联商品 添加
function add_topic_relation_product(){
	 //判断id 
	   var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    var select_value=Array();
    for( i=0;i<=parseInt(id.length)-1;i++ ){
        if(id[i].checked){
            j++;
           if(id[i].value!=""){
            select_value[i]=id[i].value;
       }
                
        }
    }
    if( j<1 ){
        if(confirm(j_please_select))
        {
            return false;
        }
    }
	
	 
	if(document.getElementById("Topic_id")){
		var topic_id=document.getElementById("Topic_id").value;
	}else{
		var topic_id=0;
	}
	var is_single = document.getElementsByName("is_single[]");
	var is_single_value = 0;
	for( var i=0;i<is_single.length;i++ ){
		if(is_single[i].checked){
			is_single_value = is_single[i].value;
		}
	}
		var newhtml ="";
	$.ajax({
		url:admin_webroot+"topics/add_topic_relation_product/",
		method:'POST',
		dataType:'json',
	data:{product_select:select_value,topic_id:topic_id,is_single_value:is_single_value},
		success:function(data){
			if(data.flag=="1"){
				  	for(i=0;i<data.content.length;i++){
 
  newhtml+="<li><img style='width:150px;height:140px;' src="+data.content[i]['img_detail']+"><br/><span><!--"+j_sort+":<span onclick='javascript:listTable.edit(this, \"articles/article_relation_product_orderby/\","+data.content[i]['id']+")'>"+data.content[i]['name']+"</span>-->"+data.content[i]['name']+"<span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);'onclick=\"drop_topic_relation_product("+data.content[i]['id']+");\"/></span></li>";
					}
					$("#relative_product").html(newhtml);
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
	}
	});
	
	
}

////////////////////////// /删除商品关联 JS

function drop_topic_relation_product(product_id ){//alert(product_id);
		var newhtml="";
		$.ajax({
			url:admin_webroot+"topics/drop_topic_relation_product/"+product_id+"/" ,
			type:"GET",
			dataType:"json",
			success:function(data){
				if(data.flag=="1"){
					for(i=0;i<data.content.length;i++){
                newhtml+="<li><img style='width:150px;height:140px;' src="+data.content[i]['img_detail']+"><br/><span><!--"+j_sort+":<span onclick='javascript:listTable.edit(this, \"articles/article_relation_product_orderby/\","+data.content[i]['id']+")'>"+data.content[i]['product_id']+"</span>-->"+data.content[i]['name']+"<span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_topic_relation_product("+data.content[i]['id']+");\"/></span></li>";
                		 }
					$("#relative_product").html(newhtml);
					return;
				}
				if(data.flag=="2"){
					alert(j_failed_delete);
				}
		}
		});
	
	
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//搜索文章
function searchArticles(){
	var article_category_id = document.getElementById("article_category_id");//文章分类
	var article_keyword = document.getElementById("article_keyword");//搜索关键字
		
	$.ajax({
		url:admin_webroot+"products/searchArticles/",
		type:"POST",
			dataType:"json",
		data:{article_category_id:article_category_id.value,article_keyword:article_keyword.value},
		success:function(data){
				if(data.flag=="1"){
		//	alert("xx");
					var article_select_sel = document.getElementById('article_select');
					$(article_select_sel).html('');
					if(data.content){
						if(data.content){
							var selhtml="";
							for(i=0;i<data.content.length;i++){
								selhtml+="<dl onclick=\"add_topic_relation_article('"+data.content[i]['Article'].id+"')\">"+data.content[i]['ArticleI18n'].title+"<span class='am-icon-plus'></span></dl>";
							}
							$(article_select_sel).html(selhtml);
				         }
			         }
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
			}
	
	});

}
//增加专题的文章
function add_topic_relation_article(intvalue){
	if(document.getElementById("Topic_id")){
		var topic_id=document.getElementById("Topic_id").value;
	}else{
		var topic_id=0;
	}
	var article_select = document.getElementById("article_select");
	var is_single2 = document.getElementsByName("is_single2[]");
	var is_single2_value = 0;
	for( var i=0;i<is_single2.length;i++ ){
		if(is_single2[i].checked){
			is_single2_value = is_single2[i].value;
		}
	}
    if(intvalue==""){
		alert(j_select_associated_article);
		return;
	}
	$.ajax({
		url:admin_webroot+"topics/add_topic_relation_article/",
		type:"POST",
		dataType:"json",
	data:{article_select:intvalue,topic_id:topic_id,is_single2_value:is_single2_value},
	success:function(data){
			if(data.flag=="1"){
					var newhtml = "";
					for(i=0;i<data.content.length;i++){
                		newhtml+="<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+data.content[i]['TopicArticle']['title']+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_topic_relation_article('"+data.content[i]['TopicArticle']['article_id']+"','"+data.content[i]['TopicArticle']['topic_id']+"');\"></span></div>";
					}
					$("#relative_article").html(newhtml);
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
	}
	});
}
/**
  * 商品关联页删除关联文章
  */
function drop_topic_relation_article(article_id,product_id){
	$.ajax({
		url:admin_webroot+"topics/drop_topic_relation_article/"+article_id+"/"+product_id,
		type:"GET",
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
					var newhtml = "";
					for(i=0;i<data.content.length;i++){
                		newhtml+="<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+data.content[i]['TopicArticle']['title']+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_topic_relation_article('"+data.content[i]['TopicArticle']['article_id']+"','"+data.content[i]['TopicArticle']['topic_id']+"');\"/></span></div>";
					}
					$("#relative_article").html(newhtml);
					return;
				}
				if(data.flag=="2"){
					alert(j_failed_delete);
				}
	}
	});
	
	
}