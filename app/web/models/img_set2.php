<?php

class ImgSet2 extends AppModel
{
    public $useTable = false;
    public $name = 'ImgSet2';
    //cm_color=TAN,NAV&cm_text=&cm_font=Goudy&cm_font-color=E800C5&

    public function pagram_init($pagram_beta)
    {
        $pagram = array();
        $pagram['bk'] = $pagram_beta['GB'];
        $pagram['kg'] = $pagram_beta['KG'];
        foreach ($pagram_beta as $k => $v) {
            if ($k == 'GB' || $k == 'GP' || $k == 'KG' || empty($v)) {
                continue;
            }
            $pagram[strtolower($k)] = $v;
        }

        return $pagram;
    }

    public function pagram_init_back($pagram_beta)
    {
        $pagram = array();
        $pagram['pm'] = $pagram_beta['GP'];

        foreach ($pagram_beta as $k => $v) {
            if ($k == 'GB' || $k == 'GP') {
                continue;
            }
            if (empty($v)) {
                continue;
            }
            if ($k == 'EWC') {
                continue;
            }
            $pagram[strtolower($k)] = $v;
        }
        $pagram['gb'] = $pagram_beta['GB'];
        if (isset($pagram_beta['EWC'])) {
            $pagram['ewc'] = $pagram_beta['EWC'];
        }

        return $pagram;
    }

    public function getImg($pagram, $logo, $logo_pagarm, $type = 1)
    {
        //		$arr=GetImageSize(IMAGES."style_1/style_1_bk_401.gif");
//		$imgn=imagecreatefromgif(IMAGES."style_1/style_1_bk_401.gif");
//		$pagram=array();
//		$pagram['bk']='401';
//		$pagram['bf']='';
//		$pagram['ft']='101';
//		$pagram['kg']='201';
//		$pagram['lg']='301';
//		$pagram['wl']='702';
        $i = $this->hand_set($pagram, 'style_1', $logo, $logo_pagarm, $type);
        //return $i;
        //echo microtime();echo "--->";die();
        return imagegif($i);
        //$this->make($imgn,"lg","301","style_1");	
    }

    public function make($imgn, $keys, $value, $style, $type)
    {
        if (!$imgn) {
            return false;
        }
        if (empty($value)) {
            return $imgn;
        }
        if ($keys == 'ewc' && $type != 1) {
            $dd = $this->make_ewc($imgn, $keys, $value, $style);

            return $dd;
        }
        $watermark = $this->get_value($value);

//		$im=imagecreatetruecolor(imagesx($imgn),imagesy($imgn));
//
//		imagecopy($im,$imgn,0,0,0,0,imagesx($imgn),imagesy($imgn));

//		for ($c = 0; $c < imagecolorstotal($imgn); $c++) {
//        $col = imagecolorsforindex($imgn, $c);
//        pr($col);
//		}
//		die();
//
//		$black=imagecolorexact($im,0,0,0);
//		imagecolortransparent($im,$black);
//		imagecopymerge($im,$watermark,0,0,0,0,241,397,100);
        //$watermarks =$this->get_value($value);
        //imagetruecolortopalette($watermark, false, 256);
//		header('Content-Type: image/gif');
//		die(imagegif($im));
        $im_x = imagesx($imgn);
        $im_y = imagesy($imgn);
        $w_x = imagesx($watermark);
        $w_y = imagesy($watermark);
        imagecopymerge($imgn, $watermark, 0, 0, 0, 0, $w_x, $w_y, 80);
        //imagegif($imgn,WWW_ROOT.'tmpfile.gif');
        return $imgn;
    }

    public function make_ewc($imgn, $keys, $value, $style)
    {
        if (!$imgn) {
            return false;
        }
        if (empty($value)) {
            return $imgn;
        }

        $watermark = $this->get_value($value);

        $im = imagecreatetruecolor(imagesx($imgn), imagesy($imgn));

        imagecopy($im, $imgn, 0, 0, 0, 0, imagesx($imgn), imagesy($imgn));

        $black = imagecolorexact($im, 0, 0, 0);
        imagecolortransparent($im, $black);
        imagecopymerge($im, $watermark, 0, 0, 0, 0, 241, 397, 100);

        return $im;
    }

    public function hand_set($pagram, $style, $logo, $logo_pagarm, $type)
    {
        $imgn = '';
        //echo "--------------------";
        foreach ($pagram as $k => $v) {
            //echo microtime();echo "--->";
            if ($this->type_check($k)) {
                $imgn = $this->make_bk($k, $v, $style);
            } else {
                $imgn = $this->make($imgn, $k, $v, $style, $type);
            }
        }
        //echo "|".microtime();echo "--->"; 
        $watermark = $this->check_type_w($logo);

        if ($watermark) {
            $type = $this->check_type($logo);
            if ($type == 'png') {
                $imgn = $this->build_sw_png($imgn, $watermark, $logo_pagarm);
            } else {
                $imgn = $this->build_sw($imgn, $watermark, $logo_pagarm);
            }
        } else {
        }
        //echo "|".microtime();echo "--->"; 
        //die();
        return $imgn;
    }

    public function check_type_w($logo)
    {
        if (empty($logo)) {
            return false;
        }
        $lastdot = strrpos($logo, '.'); //取出.最后出现的位置
        $extended = substr($logo, $lastdot + 1); //取出扩展名
        switch ($extended) {
             case 'jpg':
                $watermark = imagecreatefromjpeg($logo);
                break;
             case 'png':
                $watermark = imagecreatefrompng($logo);
                break;
             case 'bmp':
                $watermark = imagecreatefromwbmp($logo);
                break;
             case 'gif':
                $watermark = imagecreatefromgif($logo);
                break;
             default:
                $watermark = false;
                break;
        }

        return $watermark;
    }

    public function check_type($logo)
    {
        if (empty($logo)) {
            return false;
        }
        $lastdot = strrpos($logo, '.'); //取出.最后出现的位置
        $extended = substr($logo, $lastdot + 1); //取出扩展名
        $type = false;
        switch ($extended) {
             case 'jpg':
                $type = 'jpg';
                break;
             case 'png':
                $type = 'png';
                break;
             case 'bmp':
                $type = 'bmp';
                break;
             case 'gif':
                $type = 'gif';
                break;
             default:
                $type = false;
                break;
        }

        return $type;
    }

    public function build_sw_png($imgn, $watermark, $pa)
    {
        $watermark = $this->resizeImage($watermark, 60, 70, 'test', 'png');
        imagetruecolortopalette($watermark, false, 256);
        $black = imagecolorexact($watermark, 4, 2, 4);
        $tmp = imagerotate($watermark, $pa[0], $black);
        if ($tmp) {
            $watermark = imagerotate($watermark, $pa[0], $black);
            imagedestroy($tmp);
            imagetruecolortopalette($watermark, false, 256);
            $black = imagecolorexact($watermark, 4, 2, 4);
        } else {
            $black = $this->imagegetcolor($watermark, 4, 2, 4);
            $watermark = imagerotate($watermark, $pa[0], $black);
            imagetruecolortopalette($watermark, false, 256);
            $black = $this->imagegetcolor($watermark, 4, 2, 4);
        }
        imagecolortransparent($watermark, $black);
        ImageColorSet($watermark, $black, 0, 0, 0);
        imagetruecolortopalette($watermark, false, 256);
        $im_x = imagesx($imgn);
        $im_y = imagesy($imgn);
        $w_x = imagesx($watermark);
        $w_y = imagesy($watermark);
        imagecopymerge($imgn, $watermark, $pa[1], $pa[2], 0, 0, $w_x, $w_y, 90);

        return $imgn;
    }

    public function build_sw($imgn, $watermark, $logo_pagarm)
    {
        $watermark = $this->resizeImage($watermark, 60, 80, 'test', 'jpg');
        imagetruecolortopalette($watermark, false, 256);

        $white = imagecolorexact($watermark, 252, 254, 252);

        $tmp = imagerotate($watermark, $logo_pagarm[0], $white);
        if ($tmp) {
            $watermark = imagerotate($watermark, $logo_pagarm[0], $white);
            imagetruecolortopalette($watermark, false, 256);
            $white = imagecolorexact($watermark, 252, 254, 252);
            imagedestroy($tmp);
        } else {
            $white = $this->imagegetcolor($watermark, 252, 254, 252);
            $watermark = imagerotate($watermark, $logo_pagarm[0], $white);
            imagetruecolortopalette($watermark, false, 256);
            $white = $this->imagegetcolor($watermark, 252, 254, 252);
        }

        imagecolortransparent($watermark, $white);
        ImageColorSet($watermark, $white, 0, 0, 0);

        $w_x = imagesx($watermark);
        $w_y = imagesy($watermark);

        imagecopymerge($imgn, $watermark, $logo_pagarm[1], $logo_pagarm[2], 0, 0, $w_x, $w_y, 100);
        //imagegif($imgn,WWW_ROOT.'img'.DS.'ytmpfile.gif');
        return $imgn;
        //imagepng($watermark,WWW_ROOT.'img'.DS.'xtmpfile.png');
    }

    public function type_check($keys)
    {
        $type = array('bk','pm');
        if (in_array($keys, $type)) {
            return true;
        } else {
            return false;
        }
    }

    public function make_bk($keys, $value, $style)
    {
        //echo "---------------------";
        if (empty($value)) {
            return false;
        } else {
            $imgn = $this->get_value($value);
        }

        return $imgn;
    }

    public function imagegetcolor($im, $r, $g, $b)
    {
        $c = imagecolorexact($im, $r, $g, $b);
        if ($c != -1) {
            return $c;
        }
        $c = imagecolorallocate($im, $r, $g, $b);
        if ($c != -1) {
            return $c;
        }

        return imagecolorclosest($im, $r, $g, $b);
    }

    public function get_value($value, $mode = true)
    {
        $cache_config = 'short';

        if ($mode) {
            $paramsHash = md5(serialize($value));
            $cache_key = 'pic'.'_'.$paramsHash.'_file';
            $foo = cache::read($cache_key, $cache_config);
            //var_dump($foo);echo ";";$d=file_get_contents($value); imagecreatefromstring($d);die();
            //$foo = false;
            if ($foo) {
                $foo = imagecreatefromstring($foo);

                return $foo;
            } else {
                $foo = file_get_contents($value);
                cache::write($cache_key, $foo, $cache_config); //写入缓存			
                return imagecreatefromgif($value);
            }
        }

        return imagecreatefromgif($value);
    }

    /*等比例缩放
    $im 图片对象，
    应用函数之前，需要用imagecreatefromjpeg()读取图片对象，如果PHP环境支持PNG，GIF，
    也可使用imagecreatefromgif()，imagecreatefrompng()；
    $maxwidth 定义生成图片的最大宽度（单位：像素）
    $maxheight 生成图片的最大高度（单位：像素）
    $name 生成的图片名
    $filetype 最终生成的图片类型
    */
    public function resizeImage($im, $maxwidth, $maxheight, $name, $filetp)
    {
        $pic_width = imagesx($im);
        $pic_height = imagesy($im);
        $resizeheight_tag = '';
        $resizewidth_tag = '';
        if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
            if ($maxwidth && $pic_width > $maxwidth) {
                $widthratio = $maxwidth / $pic_width;
                $resizewidth_tag = true;
            }

            if ($maxheight && $pic_height > $maxheight) {
                $heightratio = $maxheight / $pic_height;
                $resizeheight_tag = true;
            }
            if ($resizewidth_tag && $resizeheight_tag) {
                if ($widthratio < $heightratio) {
                    $ratio = $widthratio;
                } else {
                    $ratio = $heightratio;
                }
            }

            if ($resizewidth_tag && !$resizeheight_tag) {
                $ratio = $widthratio;
            }
            if ($resizeheight_tag && !$resizewidth_tag) {
                $ratio = $heightratio;
            }

            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;

            if (function_exists('imagecopyresampled')) {
                $newim = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
            } else {
                $newim = imagecreate($newwidth, $newheight);
                imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
            }

            //$name = $name.$filetp;
            //imagejpeg($newim,$name);
            return $newim;
            imagedestroy($newim);
        } else {
            $name = $name.$filetp;

            return $im;
            //imagejpeg($im,$name);
        }
    }
}
