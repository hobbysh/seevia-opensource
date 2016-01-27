<?php echo $html->css('/skins/default/css/codemirror');?>
<?php echo $html->css('/skins/default/css/docs');?>
<?php echo $javascript->link('/skins/default/js/codemirror');?>
<?php echo $javascript->link('/skins/default/js/css');?>
<?php echo $form->create('themes',array('action'=>'edit_css/'.$id));?>
<div class="tablemain" style="margin-left:0;">
<textarea id="code" name="css_info"><?php echo isset($css_info)?$css_info:''?></textarea>
<div class="btnouter"><input type="submit" value="保存" /><input type="reset" value="取消" /></div>
</div>
<?php echo $form->end();?>
<script>
      var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true
      });
</script>
