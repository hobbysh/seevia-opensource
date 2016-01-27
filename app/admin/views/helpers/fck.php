<?php

class FckHelper extends Helper
{
    public function load($id, $toolbar = 'Default')
    {
        $did = '';
        foreach (explode('/', $id) as $v) {
            $did .= ucfirst($v);
        }

        return "
		<script type=\"text/javascript\">
		fckLoader_$did = function () {
			var bFCKeditor_$did = new FCKeditor('$did');
			bFCKeditor_$did.BasePath = '".$this->webroot."js/fckeditor/';
			bFCKeditor_$did.ToolbarSet = '$toolbar';
			bFCKeditor_$did.Height = '400';
			bFCKeditor_$did.ReplaceTextarea();
		}
		fckLoader_$did();
		</script>";
    }
}
