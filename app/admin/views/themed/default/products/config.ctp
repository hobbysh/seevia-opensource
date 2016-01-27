<?php
	echo $form->create('products',array('action'=>'config','enctype'=>"multipart/form-data"));
	echo $this->element('config');
	echo $form->end();
?>