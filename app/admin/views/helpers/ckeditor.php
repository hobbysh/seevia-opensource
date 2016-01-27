<?php

class CkeditorHelper extends Helper
{
    public function load($id)
    {
        return "
		<script type=\"text/javascript\">
			CKEDITOR.replace( '".$id."',
				{
						skin : 'v2'
				});
		</script>";
    }
}
