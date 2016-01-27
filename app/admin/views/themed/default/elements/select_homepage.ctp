<select id="homepage" name="homepage" onChange="changeHome()" data-am-selected="{maxHeight:280}">
    <option value=""><?php echo $ld['please_choose']?></option>
    <option value="home"><?php echo $ld['default']?></option>
    <option value="homeone"><?php echo $ld['page_layout1']?></option>
    <option value="hometwo"><?php echo $ld['page_layout2']?></option>
    <option value="homethree"><?php echo $ld['page_layout3']?></option>
    <option value="hometopics"><?php echo $ld['topics'].$ld['shopping_home']?></option>
    <option value="CMS"><?php echo $ld['cms_page']?></option>
    <option value="homepage"><?php echo $ld['ad_page']?></option>
    <option value="PC"><?php echo $ld['product_categories']?></option>
    <option value="PRODUCT"><?php echo $ld['product']?></option>
    <option value="AC"><?php echo $ld['article_categories']?></option>
    <option value="ARTICLE"><?php echo $ld['article']?></option>
    <option value="TOPICS"><?php echo $ld['topics']?></option>
    <option value="TOPIC"><?php echo $ld['topics_page']?></option>
    <option value="PROMOTIONS"><?php echo $ld['promotion_list']?></option>
    <option value="PROMOTION"><?php echo $ld['promotion_page']?></option>
    <option value="LOGIN"><?php echo $ld['login']?></option>
    <option value="DEFINE"><?php echo $ld['page_layout0']?></option>
    <option value="custom_made"><?php echo '定制首页'?></option>
</select>
<div id="home_div" style="margin:0.7rem auto;display:none;"></div>
<script type="text/javascript">
function changeHome(){
	var route=document.getElementById("homepage").value;
	var num = "<?php echo count($backend_locales);?>";
 	document.getElementById("action").readOnly=true;
 	document.getElementById("controller").readOnly=true;
 	document.getElementById("modelID").readOnly=true;
    if(route==""){
        $("#home_div").html("").hide();
        return;
    }
	if(route =='home'||route=='homeone'||route=='hometwo'||route=='homethree'||route=='hometopics'||route=='homepage'||route=='custom_made'){
		document.getElementById("action").value=route;
 		document.getElementById("controller").value='pages';
 		document.getElementById("modelID").value='';
 		document.getElementById("home_div").innerHTML="";
 		document.getElementById("home_div").style.display = "none";
 		return;
	}
	if(route =='CMS'){
 		document.getElementById("controller").value='articles';
 		document.getElementById("action").value='home';
 		document.getElementById("modelID").value='';
 		document.getElementById("home_div").innerHTML="";
 		document.getElementById("home_div").style.display = "none";
 		return;
	}
	if(route =='TOPICS'){
 		document.getElementById("controller").value='topics';
 		document.getElementById("action").value='index';
 		document.getElementById("modelID").value='';
 		document.getElementById("home_div").innerHTML="";
 		document.getElementById("home_div").style.display = "none";
 		return;
	}
	if(route =='PROMOTIONS'){
 		document.getElementById("controller").value='promotions';
 		document.getElementById("action").value='index';
 		document.getElementById("modelID").value='';
 		document.getElementById("home_div").innerHTML="";
 		document.getElementById("home_div").style.display = "none";
 		return;
	}
	if(route =='LOGIN'){
 		document.getElementById("controller").value='users';
 		document.getElementById("action").value='login';
 		document.getElementById("modelID").value='';
 		document.getElementById("home_div").innerHTML="";
 		document.getElementById("home_div").style.display = "none";
 		return;
	}
	if(route =='PRODUCT'){
 		document.getElementById("controller").value='products';
 		document.getElementById("action").value='view';
	}
	if(route =='ARTICLE'){
 		document.getElementById("controller").value='articles';
 		document.getElementById("action").value='view';
	}
	if(route =='TOPIC'){
 		document.getElementById("controller").value='topics';
 		document.getElementById("action").value='view';
	}
	if(route =='PROMOTION'){
 		document.getElementById("controller").value='promotions';
 		document.getElementById("action").value='view';
	}
	if(route =='PC'||route =='AC'){
 		document.getElementById("controller").value='categories';
 		document.getElementById("action").value='view';
	}
	if(route=="DEFINE"){
 		document.getElementById("action").readOnly=false;
 		document.getElementById("controller").readOnly=false;
 		document.getElementById("modelID").readOnly=false;
 		document.getElementById("home_div").innerHTML="";
 		document.getElementById("home_div").style.display = "none";
		return;
	}
	var key="no";
	if(document.getElementById("keyword")){
		key=document.getElementById("keyword").value;
	}
    $.ajax({
	            type: "POST",
	            url: "/admin/configvalues/changehome/"+route+'/'+key,
	            dataType: 'html',
	            data: {},
	            success: function (result) {
                    $("#home_div").html(result).show();
                    if(document.getElementById("next_homepage")){
                   		setHomepage();
                   	}
                    $("#home_div select").selected();
	            }
	        });
}
function setHomepage(){
 	$b=document.getElementById("next_homepage").value;
 	document.getElementById("modelID").value=$b;
}
</script>