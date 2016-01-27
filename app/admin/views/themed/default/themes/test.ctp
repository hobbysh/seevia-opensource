<?php
	echo $html->css('/skins/default/css/docs');
	echo $html->css('/skins/default/css/warehouse');
	echo $html->css('/skins/default/css/codemirror');
	echo $html->css('/skins/default/css/calendar/calendar');
	
	echo $javascript->link('/skins/default/js/calendar/language/'.$backend_locale);
	echo $javascript->link('/skins/default/js/calendar/calendar');
	echo $javascript->link('/skins/default/js/jquery-1.8.2.min');
	echo $javascript->link('/skins/default/js/codemirror');
 	echo $javascript->link('/skins/default/js/css');
?>
<div id="tablemain" class="tablemain">
	<div>
		<h2>aaa</h2>
		<div>
			<textarea id="mobile_css"><?php echo $template_list['Template']['mobile_css']['mobile_comment_css']; ?></textarea>
		</div>
	</div>
	<div>
		<h2>bbb</h2>
		<div>
			<textarea id="show_css"><?php echo $template_list['Template']['show_css']; ?></textarea>
		</div>
	</div>
</div>
<script>
$(function(){
	var editor1 = CodeMirror.fromTextArea(document.getElementById("show_css"), {
		lineNumbers: true
	});
	var editor2 = CodeMirror.fromTextArea(document.getElementById("mobile_css"), {
		lineNumbers: true
	});
})
$("#tablemenu li").live("click",function(){
	var editor1 = CodeMirror.fromTextArea(document.getElementById("show_css"), {
		lineNumbers: true
	});
	var editor2 = CodeMirror.fromTextArea(document.getElementById("mobile_css"), {
		lineNumbers: true
	});
});
</script>