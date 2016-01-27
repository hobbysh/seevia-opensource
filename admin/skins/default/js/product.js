/**
  * 添加扩展分类
  */
function addOtherCat(){
	var conObj = document.getElementById("othercat");
	var sel = document.createElement("SELECT");
	var br = document.createElement("br");
	var selCat = document.getElementById("product_category_id");
	for (i = 0; i < selCat.length; i++){
		var opt = document.createElement("OPTION");
		opt.text = selCat.options[i].text;
		opt.value = selCat.options[i].value;
		if (!!(window.attachEvent && navigator.userAgent.indexOf('Opera') === -1) ){
			sel.add(opt);
		}else{
      		sel.appendChild(opt);
		}
	}
	conObj.appendChild(sel);
	conObj.appendChild(br);
	sel.name = "other_cat[]";
	sel.onChange = function() {checkIsLeaf(this);};
}

/**
  * 添加材料
  */
function addMaterial(){
	var conObj = document.getElementById("material_list");
	var selCat = document.getElementById("material_code");
	var MaterialMax=selCat.options.length-1;
	var MaterialLength=$(".material_list .material_info").length;
	
	if(MaterialLength>=MaterialMax){return false;}
	
	var divObj = document.createElement("div");
	divObj.className = 'material_info';
	
	var labelobj1=document.createElement("label");
	labelobj1.className = 'am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label';
	labelobj1.innerHTML ="<span class='am-icon-minus' onclick='removeMaterial(this)'></span>";
	
	var childdivobj1=document.createElement("div");
	childdivobj1.className = 'am-u-lg-3 am-u-md-3 am-u-sm-3';
	
	var sel = document.createElement("SELECT");
	sel.name = "material_code[]";
	sel.className = "material_code";
	
	for (i = 0; i < selCat.options.length; i++){
		var opt = document.createElement("OPTION");
		opt.text = selCat.options[i].text;
		opt.value = selCat.options[i].value;
		if (!!(window.attachEvent && navigator.userAgent.indexOf('Opera') === -1) ){
			sel.add(opt);
		}else{
      		sel.appendChild(opt);
		}
	}
	childdivobj1.appendChild(sel);
	
	var labelobj2=document.createElement("label");
	labelobj2.className = 'am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label unit';
	labelobj2.innerHTML =j_usage_amount;
	
	var childdivobj2=document.createElement("div");
	childdivobj2.className = 'am-u-lg-3 am-u-md-3 am-u-sm-3';
	
	var text = document.createElement("input");
	text.type="text";
	text.name="material_qty[]";
	text.className="material_qty"
	childdivobj2.appendChild(text);
	
	var cfdiv=document.createElement("div");
	cfdiv.className="am-cf";
	
	divObj.appendChild(labelobj1);
	divObj.appendChild(childdivobj1);
	divObj.appendChild(labelobj2);
	divObj.appendChild(childdivobj2);
	divObj.appendChild(cfdiv);
	
	conObj.appendChild(divObj);
}

/**
* 删除材料
*/
function removeMaterial(obj){
	$(obj).parent().parent().remove();
}


/**
  * 检查是否底级分类
  */
function checkIsLeaf(selObj){
	if(selObj.options[selObj.options.selectedIndex].className != 'leafCat'){
		alert(goods_cat_not_leaf);
		selObj.options.selectedIndex = 0;
	}
}

function handlePromote(checked){
	document.getElementById('promote_price').disabled = !checked;
	document.getElementsByName('start_date')[0].disabled = !checked;
	document.getElementsByName('end_date')[0].disabled = !checked;
}

/**
  * 按比例计算价格
  * @param   string  inputName   输入框ID
  * @param   float   rate        比例
  * @param   string  priceName   价格输入框ID（如果没有取shop_price）
  */
function computePrice(inputName, rate, priceName){
	var shopPrice = priceName == undefined ? document.getElementById('shop_price').value : document.getElementById(priceName).value;
	shopPrice = $.trim(shopPrice) != '' ? parseFloat(shopPrice)* rate : 0;
	shopPrice += "";
	n = shopPrice.lastIndexOf(".");
	if(n > -1){
		shopPrice = shopPrice.substr(0, n + 3);
    }
    document.getElementById(inputName).value = shopPrice;
}

/**
  * 根据市场价格，计算并改变商店价格、积分以及会员价格
  */
function marketPriceSetted(){
	computePrice('shop_price', 1/marketPriceRate, 'market_price');
	computePrice('point_fee', integralPercent / 100);
}

/**
  * 设置了一个商品价格，改变市场价格、积分以及会员价格
  */
function priceSetted(){
	computePrice('market_price', marketPriceRate);
	document.getElementById('point_fee').value =document.getElementById('shop_price').value *document.getElementById('point').value;
}

/**
  * 将市场价格取整
  */
function integral_market_price(){
	document.getElementById('market_price').value = parseInt(document.getElementById('market_price').value);
}

function getAttrList(productId){
	var selProductsType = document.getElementById('product_type');
   	if(selProductsType != undefined){
		var ProductsType = selProductsType.options[selProductsType.selectedIndex].value;
	   	var sUrl = admin_webroot+"products/get_attr/"+productId+"/"+ProductsType;//访问的URL地址
	   	$.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'html',
            data: "",
            success: function (json) {
				document.getElementById('productsAttrdiv').innerHTML= json;
				var html = json;
    			var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
    			var match;
    			while(match = re.exec(html)){
    				if(match[2] && match[2].length > 0){
    					if(window.execScript) {
    			    		window.execScript(match[2]);
    					} else {
    					    window.eval(match[2]);
    					}
    				}
    			}
            }
        });
    }
}

/**
  * 新增一个规格
  */
function addSpec(obj,attr_id){
	var myDate = new Date();
	var m=myDate.getMinutes();
	var s=myDate.getSeconds();
	var id="clone_"+m+s+myDate.getMilliseconds();
	var T=document.getElementById(obj);
	var Table=T.cloneNode(true);
	Table=Table.innerHTML.replace(/(.*)(addSpec)(.*)(\[)(\+)/i, "$1removeSpec('"+id+"')$3$4-");
	var reg_id=myDate.getMilliseconds();
	if(attr_id==undefined){
		//Table=Table.replace(/\[]/g, "["+reg_id+"]");
	}
	for (i = 0; i < 3; i++){
		Table=Table.replace("attr_id_list["+attr_id+"]", "attr_id_list["+reg_id+"]");
		Table=Table.replace("attr_value_list["+attr_id+"]", "attr_value_list["+reg_id+"]");
		Table=Table.replace("attr_locale_list["+attr_id+"]", "attr_locale_list["+reg_id+"]");
		Table=Table.replace("attr_price_list["+attr_id+"]", "attr_price_list["+reg_id+"]");
		Table=Table.replace("attr_orderby_list["+attr_id+"]", "attr_orderby_list["+reg_id+"]");
		Table=Table.replace("attr_value_upload_size["+attr_id+"]", "attr_value_upload_size["+reg_id+"]");
		Table=Table.replace("clone_attr_id_list["+attr_id+"]", "clone_attr_id_list["+reg_id+"]");
		Table=Table.replace("clone_attr_value_list["+attr_id+"]", "clone_attr_value_list["+reg_id+"]");
		Table=Table.replace("clone_attr_locale_list["+attr_id+"]", "clone_attr_locale_list["+reg_id+"]");
		Table=Table.replace("clone_attr_price_list["+attr_id+"]", "clone_attr_price_list["+reg_id+"]");
		Table=Table.replace("clone_attr_orderby_list["+attr_id+"]", "clone_attr_orderby_list["+reg_id+"]");
		Table=Table.replace("clone_attr_value_upload_size["+attr_id+"]", "clone_attr_value_upload_size["+reg_id+"]");
		Table=Table.replace("attr_color_css_list["+attr_id+"]", "attr_color_css_list["+reg_id+"]");
		Table=Table.replace("attr_shell_num_list["+attr_id+"]", "attr_shell_num_list["+reg_id+"]");
		Table=Table.replace("show_attr_image_path_list["+attr_id+"]", "show_attr_image_path_list["+reg_id+"]");
		Table=Table.replace("show_attr_back_image_path_list["+attr_id+"]", "show_attr_back_image_path_list["+reg_id+"]");
		Table=Table.replace("show_attr_related_image_path_list["+attr_id+"]", "show_attr_related_image_path_list["+reg_id+"]");
		Table=Table.replace("show_attr_related_back_image_path_list["+attr_id+"]", "show_attr_related_back_image_path_list["+reg_id+"]");
	}
	for (i = 0; i < 12; i++){
		Table=Table.replace("attr_image_path_list["+attr_id+"]", "attr_image_path_list["+reg_id+"]");
		Table=Table.replace("attr_back_image_path_list["+attr_id+"]", "attr_back_image_path_list["+reg_id+"]");
		Table=Table.replace("attr_related_image_path_list["+attr_id+"]", "attr_related_image_path_list["+reg_id+"]");
		Table=Table.replace("attr_related_back_image_path_list["+attr_id+"]", "attr_related_back_image_path_list["+reg_id+"]");
	}
	Table="<table id="+id+">"+Table+"</table>";
//	YUI().use("io","node",function(Y) {
//			var node = Y.one(T);
//			node.insert(Table,'after');
//	});
	return;
}

/**
  * 删除规格值
  */
function removeSpec(obj){
//	var T=document.getElementById(obj);
//	YUI().use("io","node",function(Y) {
//		var node = Y.one(T);
//		node.remove(node);
//	});
//	return;
}

//商品关联商品start
/**
  * 商品关联页删除关联商品
  */
function drop_product_relation_product(product_relation_product_id,product_id){
	var sUrl = admin_webroot+"products/drop_product_relation_product/"+product_relation_product_id+"/"+product_id;//访问的URL地址
	var newhtml = "";
	$.ajax({
        type: "GET",
        url: sUrl,
        dataType: 'json',
        success: function (result) {
            if(result.flag=="1"){
				for(i=0;i<result.content.length;i++){
                	newhtml+="<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['ProductRelation'].name+"</div><!--"+j_sort+":<span onclick='javascript:listTable.edit(this, \"products/product_relation_product_orderby/\","+result.content[i]['ProductRelation']['id']+")'>"+result.content[i]['ProductRelation']['orderby']+"</span>--><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_product_relation_product("+result.content[i]['ProductRelation']['id']+","+result.content[i]['ProductRelation']['product_id']+");\"/></span></div>";
				}
				$("#relative_product").html(newhtml);
				return;
			}
			if(result.flag=="2"){
				alert(j_failed_delete);
			}
        }
    });
}

/**
  * 商品详细页关联商品，搜索商品
  */
function searchProducts(){
	var category_id = document.getElementById("category_id");//商品分类
	if(document.getElementById("productid")){
		var productid = document.getElementById("productid").value;//该商品id
	}else{
		var productid =0;
	}
	if(document.getElementById("brand_id")){
		var brand_id = document.getElementById("brand_id").value;//商品品牌
	}else{
		var brand_id ='0';
	}
	var product_keyword = document.getElementById("product_keyword");//搜索关键字
	var sUrl = admin_webroot+"products/searchProducts/";//访问的URL地址
	$.ajax({
    	type: "POST",
        url: sUrl,
        dataType: 'json',
        data: {category_id:category_id.value,brand_id:brand_id,product_keyword:product_keyword.value,productid:productid},
        success: function (result) {
            if(result.flag=="1"){
				var product_select_sel = document.getElementById('product_select');
				product_select_sel.innerHTML = "";
				if(result.content){
					var selhtml="";
					for(i=0;i<result.content.length;i++){
						selhtml+="<dl onclick=\"add_product_relation_product('"+productid+"','"+result.content[i]['Product'].id+"')\"><span class='am-icon-plus'></span>"+result.content[i]['Product'].code+"--"+result.content[i]['ProductI18n'].name+"</dl>";
					}
					product_select_sel.innerHTML = selhtml;
			    }
				return;
			}
			if(result.flag=="2"){
				alert(result.content);
			}
        }
    });
}

//编辑页 关联商品 添加
function add_product_relation_product(product_id,intvalue){
	var is_single = document.getElementsByName("is_single[]");
	var is_single_value = 0;
	for( var i=0;i<is_single.length;i++ ){
		if(is_single[i].checked){
			is_single_value = is_single[i].value;
		}
	}
	if(intvalue==""){
		alert(j_please_select+j_related_products);
		return;
	}
	var newhtml = "";
	var sUrl = admin_webroot+"products/add_product_relation_product/";//访问的URL地址
	$.ajax({
		type: "POST",
		url:sUrl,
		dataType: 'json',
		data: {product_select:intvalue,product_id:product_id,is_single_value:is_single_value},
		success: function (result) {
	    	if(result.flag=="1"){
				for(i=0;i<result.content.length;i++){
            		newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_product_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['ProductRelation'].name+"</div><!--"+j_sort+":<span onclick='javascript:listTable.edit(this, \"products/product_relation_product_orderby/\","+result.content[i]['ProductRelation']['id']+")'>"+result.content[i]['ProductRelation']['orderby']+"</span>--><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_product_relation_product("+result.content[i]['ProductRelation']['id']+","+result.content[i]['ProductRelation']['product_id']+");\"/></span></div></div>";
				}
				$("#relative_product").html(newhtml);
				return;
			}
			if(result.flag=="2"){
				alert(result.content);
			}
	    }
	});
}

//商品关联商品end
//搜索文章
function searchArticles(){
	var article_category_id = document.getElementById("article_category_id");//文章分类
	var article_keyword = document.getElementById("article_keyword");//搜索关键字
	if(document.getElementById("productid")){
		var productid = document.getElementById("productid").value;//该商品id
	}else{
		var productid =0;
	}
	var sUrl = admin_webroot+"products/searchArticles/";//访问的URL地址
	$.ajax({
		type: "POST",
		url:sUrl,
		dataType: 'json',
		data: {article_category_id:article_category_id.value,article_keyword:article_keyword.value},
		success: function (result) {
	    	if(result.flag=="1"){
				var article_select_sel = document.getElementById('article_select');
				article_select_sel.innerHTML = "";
				if(result.content){
					var selhtml="";
					for(i=0;i<result.content.length;i++){
						selhtml+="<dl onclick=\"add_product_relation_article('"+productid+"','"+result.content[i]['Article'].id+"')\"><span class='am-icon-plus'></span>"+result.content[i]['ArticleI18n'].title+"</dl>";
					}
					article_select_sel.innerHTML = selhtml;
				}
				return;
			}
			if(result.flag=="2"){
				alert(result.content);
			}
	    }
	});
}

function add_product_relation_article(product_id,intvalue){
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
	var newhtml = "";
	var sUrl = admin_webroot+"products/add_product_relation_article/";//访问的URL地址
	$.ajax({
		type: "POST",
		url:sUrl,
		dataType: 'json',
		data: {article_select:intvalue,product_id:product_id,is_single2_value:is_single2_value},
		success: function (result) {
	    	if(result.flag=="1"){
				for(i=0;i<result.content.length;i++){
               		newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_article_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['ProductArticle'].title+"</div><!--"+j_sort+":<span onclick='javascript:listTable.edit(this, \"products/product_relation_article_orderby/\","+result.content[i]['ProductArticle']['id']+")'>"+result.content[i]['ProductArticle']['orderby']+"</span>--><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_product_relation_article("+result.content[i]['ProductArticle']['id']+","+result.content[i]['ProductArticle']['product_id']+");\"/></span></div></div>";
				}
				$("#relative_article").html(newhtml);
				return;
			}
			if(result.flag=="2"){
				alert(result.content);
			}
	    }
	});
}

/**
  * 商品关联页删除关联文章
  */
function drop_product_relation_article(product_relation_article_id,product_id){
	var sUrl = admin_webroot+"products/drop_product_relation_article/"+product_relation_article_id+"/"+product_id;//访问的URL地址
	var newhtml = "";
	$.ajax({
		type: "GET",
		url: sUrl,
		dataType: 'json',
		success: function (result) {
	    	if(result.flag=="1"){
				for(i=0;i<result.content.length;i++){
               		newhtml+="<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['ProductArticle'].title+"</div><!--"+j_sort+":<span onclick='javascript:listTable.edit(this, \"products/product_relation_article_orderby/\","+result.content[i]['ProductArticle']['id']+")'>"+result.content[i]['ProductArticle']['orderby']+"</span>--><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"drop_product_relation_article("+result.content[i]['ProductArticle']['id']+","+result.content[i]['ProductArticle']['product_id']+");\"/></span></div>";
				}
				$("#relative_article").html(newhtml);
				return;
			}
			if(result.flag=="2"){
				alert(j_failed_delete);
			}
	    }
	});
}

//批量操作
function batch_operations(id){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var barch_opration_select = document.getElementById("barch_opration_select");
	var export_csv = document.getElementById("export_csv");
	var export_type = document.getElementById("export_type");
	var export_type_re = document.getElementById("export_type_re");
	var group="";
	if(barch_opration_select.value==0 ){
		alert(j_select_operation_type);
		return;
	}
	var checkboxes=new Array();
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			checkboxes.push(bratch_operat_check[i].value);
		}
	}//选中的编辑成一个字符串
    if(barch_opration_select.value!='export_csv'&&checkboxes=="" ){
		alert(j_please_select+j_product);
		return;
	}
    if(barch_opration_select.value == "export_csv"&&export_csv.value == "choice_export"){
	   if(checkboxes=="" ){
			alert(j_please_select+j_product);
			return;
		}
		
    }
	var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
	if(barch_opration_select.value == "export_csv"){
		if(export_csv.value == "category_export"){
			group="ProductCategory";
		}else{
			group="Product";
		}
		var func="/profiles/getdropdownlist/";
		//ajax传值绑定下拉
		var sUrl = admin_webroot+func;//访问的URL地址
		$.ajax({
			type: "POST",
			url:sUrl,
			dataType: 'json',
			data: {group:group},
			success: function (result) {
		    	if(result.flag == 1){
					var result_content = (result.flag == 1) ? result.content : "";
					strbind(result_content);
					$("#profilegroup").selectIt();
				}
				if(result.flag == 2){
					alert(result.content);
				}
		    }
		});
		//	弹窗
		$("#placement").modal('open');
	}else{
		if(confirm('Are you sure to '+strsel+"？")){
		   if(barch_opration_select.value=="product_cat"){
			  	var　tempForm　=　document.createElement("form");
				var sUrl = admin_webroot+"fenxiao_products/addProducts/";//访问的URL地址
				sUrl+="?productcatId="+document.getElementById("product_cat").value;
				var postData = "";
				for(var i=0;i<bratch_operat_check.length;i++){
					if(bratch_operat_check[i].checked){
						postData+=bratch_operat_check[i].value+",";
					}
				}//选中的编辑成一个字符串
				postData= postData.substring(0,postData.length-1);
				sUrl+="&pid="+postData;
				tempForm.action=sUrl;
				tempForm.method="post";
				document.body.appendChild(tempForm);
				tempForm.submit();
		  	} else {
			  	if(barch_opration_select.value=="quotes_edit"){
		   	 		document.getElementById('export_act_flag').value='';
					var postData = "";
					for(var i=0;i<bratch_operat_check.length;i++){
						if(bratch_operat_check[i].checked){
							postData+=bratch_operat_check[i].value+",";
						}
					}//选中的编辑成一个字符串
					postData= postData.substring(0,postData.length-1);
					window.location.href="/admin/quotes/view/?pid="+postData;
		   	 	}else{
		   	 		var list=[];
					for(var i=0;i<bratch_operat_check.length;i++){
						if(bratch_operat_check[i].checked){
							list.push(bratch_operat_check[i].value);
						}
					}
		   	 		if(barch_opration_select.value=="transfer_category"){
			   	 		var postcategory = "";
			   	 		var postcheck="";
						//选中的编辑成一个字符串
						postData={'checkboxes[]':list,'category_id':document.getElementById("transfer_category").value};
					}else{
						postData={'checkboxes[]':list};
					}
		   	 		$.ajax({
		   	 			url:admin_webroot+"products/batch_operations/"+barch_opration_select.value,
		   	 			type:"POST",
		   	 			data:postData,
		   	 			dataType:"html",
			   	 		success:function(result){
			   	 			alert('ok');
							window.location.href = window.location.href;		
			   	 		}
		   	 		});
				}
			}
		}
	}
}

function pop(id){
	//弹窗
	if(!document.getElementById("popup")){
		var popcontent=document.createElement('div');
		popcontent.id='popup';
		popcontent.className='popup';
		document.body.appendChild(popcontent);
	}
	var popcontent=document.getElementById("popup");
	if(arguments.length==0){popcontent.style.display="block";return;}
	var idPop=document.getElementById(id);idPop.style.display="block";
	if(arguments.length>=1){
		if(!idPop.getElementsByTagName("span")[0]||idPop.getElementsByTagName("span")[0].className!="closebtn"){
			var popCloseBtn=document.createElement("span");
			popCloseBtn.className="closebtn";
			popCloseBtn.innerHTML="×";
			idPop.insertBefore(popCloseBtn,idPop.firstChild);
		}
	}
	if(document.getElementById(id).parentNode.id!="popup"){
		var tmp=outerHTML(idPop);
		idPop.parentNode.removeChild(idPop);
		popcontent.innerHTML+=tmp;
	}
	if(arguments.length>=1){
		if(document.getElementById(id).firstChild.onclick==null){
			document.getElementById(id).firstChild.onclick=function click(event){
				$("#mod").modal('close');
			};
		}
	}
    popcontent.style.display="block";
}

//绑定下拉
function strbind(arr){
	//先清空下拉中的值
	var profilegroup=document.getElementById("profilegroup");
	for(var i=0;i <profilegroup.options.length;){
       profilegroup.removeChild(profilegroup.options[i]);
    } 
    var optiondefault=document.createElement("option");
	    profilegroup.appendChild(optiondefault);
	    optiondefault.value="0";
	    optiondefault.text=j_templates;
	for(var i=0;i<arr.length;i++){
		var option=document.createElement("option");
	    profilegroup.appendChild(option);
	    option.value=arr[i]['Profile']['code'];
	    option.text=arr[i]['ProfileI18n']['name'];
	}
}

//关闭弹窗
function btnClose1(){
	var popcontent=document.getElementById("popup");popcontent.style.display="none";var popdiv=popcontent.firstChild;popdiv.style.display="none";while(popdiv.nextSibling){var popdiv=popcontent.nextSibling;popdiv.style.display="none";}
}

//修改档案分类导出
function changeprofile(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var barch_opration_select = document.getElementById("barch_opration_select");
	var export_csv = document.getElementById("export_csv");
	var export_type = document.getElementById("export_type");
	var export_type_re = document.getElementById("export_type_re");
	var code=document.getElementById("profilegroup").value;
	if(code==0){
		alert("请选择导出方式");
		return false;
	}
	var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
	if(confirm('Are you sure to '+strsel+"？")){
		if(barch_opration_select.value == "export_csv"&&export_csv.value == "search_result"){
			var export_act_flag='1';
			if(document.getElementById('brand_id')!=null){
				var brand_id=document.getElementById('brand_id').value;
			}else{
				var brand_id=0;
			}
			if(document.getElementById('product_type_id')!=null){
				var product_type_id=document.getElementById('product_type_id').value;
			}else{
				var product_type_id=0;
			}
			var forsale=document.getElementById('forsale').value;
			var start_date_time = document.getElementsByName('start_date_time')[0].value;
			var end_date_time = document.getElementsByName('end_date_time')[0].value;
			var product_keywords=document.getElementById('product_keywords').value;
			var is_recommond=document.getElementById('is_recommond').value;
			var start_date = document.getElementsByName('start_date')[0].value;
			var end_date = document.getElementsByName('end_date')[0].value;
			var min_price=document.getElementById('min_price').value;
			var max_price=document.getElementById('max_price').value;
			var str=document.getElementsByName("box");
			var leng=str.length;
			var chestr="";
			for(i=0;i<leng;i++){
				if(str[i].checked == true){
			   		chestr+=str[i].value+",";
			  	};
			};
			var url = "export_act_flag="+export_act_flag+"&brand_id="+brand_id+"&product_type_id="+product_type_id+"&forsale="+forsale+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&product_keywords="+product_keywords+"&is_recommond="+is_recommond+"&start_date="+start_date+"&end_date="+end_date+"&min_price="+min_price+"&max_price="+max_price+"&category_id="+chestr+"&code="+code;
			window.location.href = encodeURI(admin_webroot+"products?"+url);
	   }else{
		   	document.getElementById('export_act_flag').value='';
			var postData = "";
			for(var i=0;i<bratch_operat_check.length;i++){
				if(bratch_operat_check[i].checked){
					postData+="&checkboxes[]="+bratch_operat_check[i].value;
				}
			}//选中的编辑成一个字符串
		    if(barch_opration_select.value=="export_csv"){
		     	 var　tempForm　=　document.createElement("form");
		     	 var sUrl = admin_webroot+"products/batch_operations/"+barch_opration_select.value;//访问的URL地址
				 sUrl+="?export_csv="+document.getElementById("export_csv").value+"&export_type="+document.getElementById("export_type").value+"&export_type_re="+document.getElementById("export_type_re").value+"&code="+code;
			     tempForm.action=sUrl;
			     tempForm.method="post";
				　document.body.appendChild(tempForm);
				　var　tempInput　=　document.createElement("input");
				　tempInput.type="hidden";
				　tempInput.name="method";
				　tempInput.value=postData;
				　tempForm.appendChild(tempInput);
				  tempForm.submit();
			}
		}
	}
	$(".am-close").click();
}

//询价批量操作
function quote_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var barch_opration_select = document.getElementById("barch_opration_select");
	var export_csv = document.getElementById("export_csv");
	if(barch_opration_select.value==0 ){
		alert(j_select_operation_type);
		return;
	}
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
    if(barch_opration_select.value == "export_csv"&&export_csv.value == "choice_export"){
	   if(postData=="" ){
			alert('Please Select Quote!');
			return;
		}
    }
	var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
	if(confirm('Are you sure to '+strsel+"？")){
	   	document.getElementById('export_act_flag').value='';
		var postData = "";
		for(var i=0;i<bratch_operat_check.length;i++){
			if(bratch_operat_check[i].checked){
				postData+="&checkboxes[]="+bratch_operat_check[i].value;
			}
		}
	    if(barch_opration_select.value=="export_csv"){
	     	 var　tempForm　=　document.createElement("form");
	     	 var sUrl = admin_webroot+"quotes/batch_operations/"+barch_opration_select.value;//访问的URL地址
			 sUrl+="?export_csv="+document.getElementById("export_csv").value;
		     tempForm.action=sUrl;
		     tempForm.method="post";
			　document.body.appendChild(tempForm);
			　var　tempInput　=　document.createElement("input");
			　tempInput.type="hidden";
			　tempInput.name="method";
			　tempInput.value=postData;
			　tempForm.appendChild(tempInput);
			  tempForm.submit();
		 }
	}
}

function barch_opration_select_onchange(obj){
//	var barch_opration_select_onchange = document.getElementsByName("barch_opration_select_onchange[]");
//	for( var i=0;i<barch_opration_select_onchange.length;i++ ){
//		barch_opration_select_onchange[i].style.display = "none";
//	}
	$("select[name='barch_opration_select_onchange[]']").parent().hide();
	
	var export_csv=document.getElementById("export_csv").value;
	if(obj.value=="transfer_category"){
		$("#transfer_category").parent().show();
		$("#export_type").parent().hide();
		$("#export_type_re").parent().hide();
//		document.getElementById("transfer_category").style.display = "inline-block";
//		document.getElementById("export_type").style.display = "none";
//		document.getElementById("export_type_re").style.display = "none";
	}
	if(obj.value=="export_csv"){
		if(export_csv=="all_export_csv"||export_csv=="category_export"){
			$("#export_type").parent().show();
			$("#export_csv").parent().show();
			$("#export_type_re").parent().show();
			$("#product_cat").parent().hide();
			
//			document.getElementById("export_type").style.display = "inline-block";
//			document.getElementById("export_csv").style.display = "inline-block";
//			document.getElementById("export_type_re").style.display = "inline-block";
//			document.getElementById("product_cat").style.display = "none";
		}else{
			$("#export_csv").parent().show();
			$("#export_type_re").parent().hide();
			$("#export_type").parent().hide();
			$("#product_cat").parent().hide();
			
//			document.getElementById("export_csv").style.display = "inline-block";
//			document.getElementById("export_type_re").style.display = "none";
//			document.getElementById("export_type").style.display = "none";
//			document.getElementById("product_cat").style.display = "none";
		}
	}
	if(obj.value!="export_csv"){
		$("#export_csv").parent().hide();
		$("#export_type").parent().hide();
		$("#export_type_re").parent().hide();
		$("#product_cat").parent().hide();
		
//		document.getElementById("export_csv").style.display = "none";
//		document.getElementById("export_type").style.display = "none";
//		document.getElementById("export_type_re").style.display = "none";
//		document.getElementById("product_cat").style.display = "none";
	}
	if(obj.value=="product_cat"){
		document.getElementById("product_cat").style.display = "inline-block";
		document.getElementById("export_type").style.display = "none";
		document.getElementById("export_type_re").style.display = "none";
	}
}

function  order_opration_select_onchange(obj){
	if(obj.value=="category_export" || obj.value=="all_export_csv"){
		$("#export_type_re").parent().show();
		$("#export_type").parent().show();
//		document.getElementById("export_type_re").style.display = "inline-block";
//		document.getElementById("export_type").style.display = "inline-block";
	}else{
		$("#export_type_re").parent().hide();
		$("#export_type").parent().hide();
//		document.getElementById("export_type_re").style.display = "none";
//		document.getElementById("export_type").style.display = "none";
	}
}

//商品空间
function select_photo_galleries_loading($paging){
//	YUI().use("io",function(Y) {
//		var photo_category_id = document.getElementById("photo_category_id");
//		var photo_key_word = document.getElementById("photo_key_word");
//		var sUrl = admin_webroot+"products/select_photo_galleries/?page="+$paging+"&photo_category_id="+photo_category_id.value;//访问的URL地址
//		var cfg = {
//			method: "POST",
//			data:"photo_key_word="+photo_key_word.value
//		};
//		var request = Y.io(sUrl, cfg);//开始请求
//		var newhtml = "";
//		var handleSuccess = function(ioId, o){
//			document.getElementById("innerhtml_photo_gallery").innerHTML = o.responseText;
//		}
//		var handleFailure = function(ioId, o){
//			//alert("异步请求失败!");
//		}
//		Y.on('io:success', handleSuccess);
//		Y.on('io:failure', handleFailure);
//	});
}

//新增图片到待处理区
function img_src_return(id,src){
	document.getElementById("loadingimg_view").innerHTML = document.getElementById("loadingimg_view").innerHTML+"<li><p class='picture'><input type='hidden' name='loadingimg_view_id[]' value="+id+" /><img src="+src.src+" width='100px' /></p><p class='picture'><a href='javascript:;' onclick='del_checked(this)'>删除</a><input type='radio' value="+id+" name='pic_def'>默认</p>"+lang_desc+"<p class='picture'align='right'>排序 <input type='text' name='loadingimg_view_orderby[]' style='width:50px;border:1px solid #649776;'>&nbsp&nbsp&nbsp</p></li>";
}

//删除选中图片
function del_checked(obj){
	if(this.confirm(j_confirm_delete_photo)){
		var rep_value = obj.parentNode.parentNode.innerHTML;
		if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
			rep_value = "<li>"+rep_value+"</li>";
		}else{
			obj.parentNode.parentNode.removeNode(true)
		}
		var thispreServerData = document.getElementById("loadingimg_view");
		thispreServerDatavalue = thispreServerData.innerHTML;
		thispreServerData.innerHTML = thispreServerDatavalue.replace(rep_value," ");
		return true;
	}
}

//检测商品详细页的必填项
function product_detail_checks(){
	if(checkproCodeFlag!=null&&checkproCodeFlag==false){
		return false;
	}
	var product_name_obj = document.getElementById("product_name_"+backend_locale);
	var product_category_id = document.getElementById("product_category_id");
	var product_num = document.getElementById("product_num_h");
	var product_shop_price=document.getElementById("shop_price");
	var product_market_price=document.getElementById("market_price");
	if(product_shop_price.value<0||product_market_price.value<0){
		alert(j_enter_correct_price);
		return false;
	}
	if(product_name_obj.value==""){
		alert(j_enter_product_name);
		return false;
	}
	if(product_category_id.value==0){
		alert(j_select_product_category);
		return false;
	}
	if(product_num.value==1){
		alert(j_already_exists_sku);
		return false;
	}
	return true;
}

//删除淘宝商品
function delete_item(num_iid,obj){
//	YUI().use("io","node",function(Y) {
//		var sUrl = admin_webroot+"taobao_uploads/delete_item/"+num_iid;//访问的URL地址
//		var cfg = {
//			method: "POST"
//		};
//		var request = Y.io(sUrl, cfg);//开始请求
//		var handleSuccess = function(ioId, o){
//			if(o.responseText !== undefined){
//				try{
//					eval('result='+o.responseText);
//					alert(result.msg);
//					if(result.flag==1){
//						obj.parentNode.children[2].checked = false;
//					}
//				}catch(e){
//					alert(j_object_transform_failed);
//					alert(o.responseText);
//				}
//			}
//		}
//		var handleFailure = function(ioId, o){
//			alert("异步请求失败!");
//		}
//		Y.on('io:success', handleSuccess);
//		Y.on('io:failure', handleFailure);
//	});
}