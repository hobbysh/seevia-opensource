<?php
	echo $form->create('users',array('action'=>'config','enctype'=>"multipart/form-data"));
	echo $this->element('config');
	echo $form->end();
?>