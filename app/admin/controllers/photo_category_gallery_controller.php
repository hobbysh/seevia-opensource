<?php

class PhotoCategoryGalleryController extends AppController
{
    public $name = 'PhotoCategoryGallery';
    public $helpers = array('Html','Javascript');
    public $uses = array('PhotoCategory','PhotoCategoryI18n','PhotoCategoryGallery');

    public function photo()
    {
        $result['error'] = true;
        $result['error_img'] = 'Unknow';
        if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error'] != 0) {
            $result['error'] = true;
            $result['error_img'] = $this->ld['image_not_exist'];
            die(json_encode($result));
        } else {
            $today = date('Y_m');
            $ymd = date('Y-m-d');
            $server_name = $_SERVER['SERVER_NAME'];
            $user_ip = $_SERVER['REMOTE_ADDR'];
            //验证信息
            $md5_info = md5($server_name.'ioco');
            //取水印文件
            $watermark_file = isset($_REQUEST['watermark_file']) ? $_REQUEST['watermark_file'] : '';
            //取水印位置$this->configs
            $watermark_location = isset($_REQUEST['watermark_location']) ? $_REQUEST['watermark_location'] : '';
            //取水印透明度
            $watermark_transparency = isset($_REQUEST['watermark_transparency']) ? $_REQUEST['watermark_transparency'] : 50;
            //是否加水印
            $is_watermark = $_REQUEST['watermark'];
            //水印文字
            $water_text = isset($_REQUEST['water_text']) ? $_REQUEST['water_text'] : '';
            $water_text_font = isset($_REQUEST['water_text_font']) ? $_REQUEST['water_text_font'] : '';
            $water_text_size = isset($_REQUEST['water_text_size']) ? $_REQUEST['water_text_size'] : '';
            $water_text_color = isset($_REQUEST['water_text_color']) ? $_REQUEST['water_text_color'] : '';
            //上传商品图片是否保留原图	 	  	
            $retain_original_image_when_upload_products = 1;
            //列表缩略图宽度
            $thumbl_image_width = isset($_REQUEST['small_img_width']) ? $_REQUEST['small_img_width'] : 160;
            //列表缩略图高度
            $thumb_image_height = isset($_REQUEST['small_img_height']) ? $_REQUEST['small_img_height'] : 160;
            //中图宽度
            $image_width = isset($_REQUEST['mid_img_width']) ? $_REQUEST['mid_img_width'] : 400;
            //中图高度
            $image_height = isset($_REQUEST['mid_img_height']) ? $_REQUEST['mid_img_height'] : 400;
            //大图宽度
            $image_width_big = isset($_REQUEST['big_img_width']) ? $_REQUEST['big_img_width'] : 800;
            //大图高度
            $image_height_big = isset($_REQUEST['big_img_height']) ? $_REQUEST['big_img_height'] : 800;
            //处理文件目录start
            $imgname_arr[0] = substr($_FILES['Filedata']['name'], 0, strripos($_FILES['Filedata']['name'], '.'));
            $imgname_arr[1] = substr($_FILES['Filedata']['name'], strripos($_FILES['Filedata']['name'], '.') + 1);
            $img_thumb_watermark_name = md5($imgname_arr[0].time());// date("Ymd").time().rand(1000,9999);	
            $image_name = $img_thumb_watermark_name.'.'.$imgname_arr[1];//要改成的文件名
            $imgaddr_original = WWW_ROOT.'/media/photos/'.date('Ym').$_REQUEST['img_addr'].'/original/';   //saas/src/prod/htdocs/20111101/49/1/orginal/
            $imgaddr_detail = WWW_ROOT.'/media/photos/'.date('Ym').$_REQUEST['img_addr'].'/detail/';
            $imgaddr_big = WWW_ROOT.'/media/photos/'.date('Ym').$_REQUEST['img_addr'].'/big/';
            $imgaddr_small = WWW_ROOT.'/media/photos/'.date('Ym').$_REQUEST['img_addr'].'/small/';
            $this->mkdirs($imgaddr_original);
            $this->mkdirs($imgaddr_detail);
            $this->mkdirs($imgaddr_big);
            $this->mkdirs($imgaddr_small);
            $result['error'] = true;
            move_uploaded_file($_FILES['Filedata']['tmp_name'], iconv('UTF-8', 'GBK//IGNORE', $imgaddr_original.$_FILES['Filedata']['name']));
            $upload_img_src = $imgaddr_original;
            //重新命名图片名称
            rename(iconv('UTF-8', 'GBK//IGNORE', $upload_img_src.$_FILES['Filedata']['name']), $imgaddr_original.$image_name);
            $upload_img_src = $imgaddr_original.$image_name;
            $img_original = $imgaddr_original.$image_name;//原图地址   /saas/src/prod/htdocs/20111101/49/1/orginal/xxxx.jpg
            $img_detail = $imgaddr_detail.$image_name;//详细图 中图地址
            $img_thumb = $imgaddr_small.$image_name;//缩略图地址
            $img_big = $imgaddr_big.$image_name;//大图地址
            //商品缩略图
            $image_name = $this->make_thumb($img_original, $thumbl_image_width, $thumb_image_height, '#FFFFFF', $img_thumb_watermark_name, $imgaddr_small, $imgname_arr[1]);
            $image_name = $this->make_thumb($img_original, $image_width, $image_height, '#FFFFFF', $img_thumb_watermark_name, $imgaddr_detail, $imgname_arr[1]);
            $image_name = $this->make_thumb($img_original, $image_width_big, $image_height_big, '#FFFFFF', $img_thumb_watermark_name, $imgaddr_big, $imgname_arr[1]);
            //水印
            $WaterMark_img_address = WWW_ROOT.$watermark_file;
            if ($_REQUEST['watermark'] == 1 && $is_watermark == 1) {
                $this->imageWaterMark($img_original, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($img_detail, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($img_thumb, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($img_big, $watermark_location, $WaterMark_img_address, $watermark_transparency);
            }
            if ($_REQUEST['watermark'] == 2 && $is_watermark == 2) {
                $this->imageWaterMark($img_original, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($img_detail, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($img_thumb, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($img_big, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
            }
            //保存到数据库
            $photo_img_small = str_replace(WWW_ROOT, '', $img_thumb);
            $photo_img_detail = str_replace(WWW_ROOT, '', $img_detail);
            $photo_img_original = str_replace(WWW_ROOT, '', $img_original);
            $photo_img_big = str_replace(WWW_ROOT, '', $img_big);
            $photo_img_original_info = getimagesize($imgaddr_original.$image_name);
            $photo_name[0] = substr($_FILES['Filedata']['name'], 0, strripos($_FILES['Filedata']['name'], '.'));
            $photo_name[1] = substr($_FILES['Filedata']['name'], strripos($_FILES['Filedata']['name'], '.') + 1);
            $themes_host = Configure::read('themes_host');
            $photo_category_galleries = array(
                'photo_category_id' => isset($_REQUEST['photo_category_id']) ? $_REQUEST['photo_category_id'] : '0',
                'name' => preg_replace('/\W/', '', $photo_name[0]),
                'type' => $photo_name[1],
                'original_size' => intval($_FILES['Filedata']['size'] / 1024),
                'original_pixel' => $photo_img_original_info[0].'*'.$photo_img_original_info[1],
                'img_small' => $photo_img_small,
                'img_detail' => $photo_img_detail,
                'img_original' => $photo_img_original,
                'img_big' => $photo_img_big,
                'orderby' => '50',
            );
            $result['error'] = false;
            if (!file_exists($img_thumb)) {
                $result['error'] = true;
                $result['error_img'] = $this->ld['thumbnail_generate_failed'];
            }
            if (!file_exists($img_detail)) {
                $result['error'] = true;
                $result['error_img'] = $this->ld['detail_image_generate_failed'];
            }
            if (!file_exists($img_original)) {
                $result['error'] = true;
                $result['error_img'] = $this->ld['original_image_build_failure'];
            }
            if (!file_exists($img_big)) {
                $result['error'] = true;
                $result['error_img'] = $this->ld['big_image_generate_failure'];
            }
            if ($result['error'] == false) {
                //$now_table->saveAll($shop_gallery);
            } else {
                // @todo 删除垃圾文件 @unlink
            }
            //返回刚上传的内容图片
            if ($retain_original_image_when_upload_products == 1) {
                $result['img'] = $photo_category_galleries;
            } elseif ($retain_original_image_when_upload_products == 0) {
                @unlink($img_address);
            }
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($result));
            exit();
        }
    }

    public function product_photo()
    {
        $result['error'] = true;
        $result['error_img'] = 'Unknow';
        if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error'] != 0) {
            $result['error'] = true;
            $result['error_img'] = $this->ld['image_not_exist'];
            die(json_encode($result));
        } else {
            $_FILES['Filedata']['name'] = strtolower($_FILES['Filedata']['name']);//将图片文件后缀修改为小写
            $product_code = $_REQUEST['product_code'];
            //取水印文件
            $watermark_file = isset($_REQUEST['watermark_file']) ? $_REQUEST['watermark_file'] : '';
            //取水印位置$this->configs
            $watermark_location = isset($_REQUEST['watermark_location']) ? $_REQUEST['watermark_location'] : '';
            //取水印透明度
            $watermark_transparency = isset($_REQUEST['watermark_transparency']) ? $_REQUEST['watermark_transparency'] : 50;
            //是否加水印
            $is_watermark = $_REQUEST['watermark'];
            //水印文字
            $water_text = isset($_REQUEST['water_text']) ? $_REQUEST['water_text'] : '';
            $water_text_font = isset($_REQUEST['water_text_font']) ? $_REQUEST['water_text_font'] : '';
            $water_text_size = isset($_REQUEST['water_text_size']) ? $_REQUEST['water_text_size'] : '';
            $water_text_color = isset($_REQUEST['water_text_color']) ? $_REQUEST['water_text_color'] : '';
            //处理文件目录start
            $imgname_arr[1] = substr($_FILES['Filedata']['name'], strripos($_FILES['Filedata']['name'], '.') + 1);
            $imgaddr = WWW_ROOT.'media/360Rotation/'.$product_code.'/big/';
            $imgaddr_small = WWW_ROOT.'media/360Rotation/'.$product_code.'/';
            $this->mkdirs($imgaddr_small);
            $this->mkdirs($imgaddr);
            $result['error'] = true;
            $num = sizeof(scandir($imgaddr));
            $num = ($num > 2) ? $num - 2 : 0;
            if ($num == 0) {
                $image_name = $product_code.'.'.$imgname_arr[1];//要改成的文件名
            } elseif ($num < 10) {
                $image_name = $product_code.'_0'.$num.'.'.$imgname_arr[1];//要改成的文件名
            } else {
                $image_name = $product_code.'_'.$num.'.'.$imgname_arr[1];//要改成的文件名
            }
            $imgname_arr[0] = substr($image_name, 0, strripos($image_name, '.'));
            $result['error'] = true;
            move_uploaded_file($_FILES['Filedata']['tmp_name'], iconv('UTF-8', 'GBK//IGNORE', $imgaddr.$_FILES['Filedata']['name']));
            $upload_img_src = $imgaddr;
            //重新命名图片名称
            rename(iconv('UTF-8', 'GBK//IGNORE', $upload_img_src.$_FILES['Filedata']['name']), $imgaddr.$image_name);
            $imgaddr = $imgaddr.$image_name;//原图地址
            $image_name = $this->make_thumb($imgaddr, 400, 400, '#FFFFFF', $imgname_arr[0], $imgaddr_small, $imgname_arr[1]);
            $WaterMark_img_address = WWW_ROOT.$watermark_file;
            if ($_REQUEST['watermark'] == 1 && $is_watermark == 1) {
                $this->imageWaterMark($imgaddr, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($imgaddr_small, $watermark_location, $WaterMark_img_address, $watermark_transparency);
            }
            if ($_REQUEST['watermark'] == 2 && $is_watermark == 2) {
                $this->imageWaterMark($imgaddr, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($imgaddr_small, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
            }
            $result['error'] = false;
            if (!file_exists($imgaddr)) {
                $result['error'] = true;
                $result['error_img'] = $this->ld['thumbnail_generate_failed'];
            }
            if ($result['error'] == false) {
                $result['img'] = str_replace(WWW_ROOT, '', $imgaddr);
            } else {
                $result['img'] = '';
            }
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($result));
            exit();
        }
    }

    public function photo_replace()
    {
        if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error'] != 0) {
            $result['error'] = true;
            $result['error_img'] = '图片不存在';//$this->ld['image_not_exist'];
            die(json_encode($result));
        } else {
            $today = date('Y_m');
            $ymd = date('Y-m-d');
            $server_name = $_SERVER['SERVER_NAME'];
            $user_ip = $_SERVER['REMOTE_ADDR'];
            //验证信息
            $md5_info = md5($server_name.'ioco');
//			if(empty($_REQUEST["check_md5_info"])||$_REQUEST["check_md5_info"]!=$md5_info){
//				$result["error"] = true;
//				$result["error_img"] = '信息验证错误';//$this->ld['validation_error_message'];
//				$this->layout="ajax";
//				Configure::write('debug',0);
//				die(json_encode($result));
//			}
            /*$ip_mask_data = $this->IpMask->find("first",array("conditions"=>array("ip"=>$user_ip,"status"=>1,"effective_time >"=>date("Y-m-d H:i:s"))));
            if(!empty($ip_mask_data)){
                $result["error"] = true;
                $result["error_img"] = $user_ip." 已被屏蔽！";
                $this->layout="ajax";
                Configure::write('debug',0);
                die(json_encode($result));
            }*/
            //替换图片原图目录
            $original_url = $_REQUEST['img_original_addr'];
            //$pattern_url=$_REQUEST["img_host"];
            //$old_name     = str_replace($pattern_url,'',$original_url);
            $old_name = $original_url;
            $photoo_name = explode('/', $old_name);
            $total = sizeof($photoo_name);
            $k = $total - 1;
            $image_name = $photoo_name[$k];
            $imgname_arr = explode('.', $image_name);
            $pattern = "/(http[s]?:\/\/)(\w+\.)+\w+\//";
            $old_addr = preg_replace($pattern, '', $original_url); //相对路径
            $imgaddr_original = WWW_ROOT.str_replace('original', 'original', $old_addr);    //绝对路径
             $x = move_uploaded_file($_FILES['Filedata']['tmp_name'], iconv('UTF-8', 'GBK//IGNORE', $imgaddr_original));
           //绝对路径
            $imgaddr_small = str_replace('original', 'small', $imgaddr_original);
            $imgaddr_thumb = str_replace('original', 'small', $imgaddr_original);
            $imgaddr_detail = str_replace('original', 'detail', $imgaddr_original);
            $imgaddr_big = str_replace('original', 'big', $imgaddr_original);
           // $thumb=str_replace($image_name,'',$imgaddr_small);
            $thumb = str_replace($image_name, '', $imgaddr_thumb);
            $img_detail = str_replace($image_name, '', $imgaddr_detail);
            $big = str_replace($image_name, '', $imgaddr_big);
            //iis_sg_ioco01_com_.
            //echo $_REQUEST['img_addr'];
            //取水印文件
            $watermark_file = isset($_REQUEST['watermark_file']) ? $_REQUEST['watermark_file'] : '';
            //取水印位置$this->configs
            $watermark_location = isset($_REQUEST['watermark_location']) ? $_REQUEST['watermark_location'] : '';
            //取水印透明度
            $watermark_transparency = isset($_REQUEST['watermark_transparency']) ? $_REQUEST['watermark_transparency'] : 50;
            //是否加水印
            $is_watermark = $_REQUEST['watermark'];
            //上传商品图片是否保留原图	 	  	
            $retain_original_image_when_upload_products = 1;
            //列表缩略图宽度
            $thumbl_image_width = isset($_REQUEST['small_img_width']) ? $_REQUEST['small_img_width'] : 160;
            //列表缩略图高度
            $thumb_image_height = isset($_REQUEST['small_img_height']) ? $_REQUEST['small_img_height'] : 160;
            //中图宽度
            $image_width = isset($_REQUEST['mid_img_width']) ? $_REQUEST['mid_img_width'] : 400;
            //中图高度
            $image_height = isset($_REQUEST['mid_img_height']) ? $_REQUEST['mid_img_height'] : 400;
            //大图宽度
            $image_width_big = isset($_REQUEST['big_img_width']) ? $_REQUEST['big_img_width'] : 800;
            //大图高度
            $image_height_big = isset($_REQUEST['big_img_height']) ? $_REQUEST['big_img_height'] : 800;
            //商品缩略图
            $this->make_thumb($imgaddr_original, $thumbl_image_width, $thumb_image_height, '#FFFFFF', $imgname_arr[0], $thumb, $imgname_arr[1]);
            $this->make_thumb($imgaddr_original, $image_width, $image_height, '#FFFFFF', $imgname_arr[0], $img_detail, $imgname_arr[1]);
            $this->make_thumb($imgaddr_original, $image_width_big, $image_height_big, '#FFFFFF', $imgname_arr[0], $big, $imgname_arr[1]);
            //$result["error"] = true;
            //水印
//			$pattern = "/(http[s]?:\/\/)(\w+\.)+\w+\//";
//			$WaterMark_img_address 	= preg_replace($pattern,'',$watermark_file);
            $WaterMark_img_address = WWW_ROOT.$watermark_file;
            if ($_REQUEST['watermark'] == 1 && $is_watermark == 1) {
                $this->imageWaterMark($imgaddr_small, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($imgaddr_detail, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($imgaddr_original, $watermark_location, $WaterMark_img_address, $watermark_transparency);
                $this->imageWaterMark($imgaddr_big, $watermark_location, $WaterMark_img_address, $watermark_transparency);
            }
            if ($_REQUEST['watermark'] == 2 && $is_watermark == 2) {
                $this->imageWaterMark($imgaddr_small, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($imgaddr_detail, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($imgaddr_original, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
                $this->imageWaterMark($imgaddr_big, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
            }
            //保存到数据库
            $photo_img_small = str_replace(WWW_ROOT, '', $imgaddr_small);
            $photo_img_detail = str_replace(WWW_ROOT, '', $imgaddr_detail);
            $photo_img_original = str_replace(WWW_ROOT, '', $imgaddr_original);
            $photo_img_big = str_replace(WWW_ROOT, '', $imgaddr_big);
            $photo_img_original_info = getimagesize($imgaddr_original);
            $photo_name[0] = substr($_FILES['Filedata']['name'], 0, strripos($_FILES['Filedata']['name'], '.'));
            $photo_name[1] = substr($_FILES['Filedata']['name'], strripos($_FILES['Filedata']['name'], '.') + 1);
            $themes_host = Configure::read('themes_host');
            $photo_category_galleries = array(
                'id' => $_REQUEST['img_id'],
                'photo_category_id' => $_REQUEST['photo_category_id'],
                'name' => preg_replace('/\W/', '', $photo_name[0]),
                'type' => $photo_name[1],
                'original_size' => intval($_FILES['Filedata']['size'] / 1024),
                'original_pixel' => $photo_img_original_info[0].'*'.$photo_img_original_info[1],
                'img_small' => $photo_img_small,
                'img_detail' => $photo_img_detail,
                'img_original' => $photo_img_original,
                'img_big' => $photo_img_big,
                //"orderby"=>"50",
            );
            //$shop_img_id = $this->ShopGallery->find("first",array("conditions"=>array("img_original"=>$img_original)));
            //$id=isset($shop_img_id["ShopGallery"])?$shop_img_id["ShopGallery"]["id"]:"";
            $id = '';
            //保存图片服务器
//			$shop_gallery = array(
//				"id"=>$id,
//				"shop_url"=>$_REQUEST["shop_server_host"],
//				"img_url"=>"http://".$server_name,
//				"type"=>$photo_name[1],
//				"original_size"=>intval($_FILES["Filedata"]["size"]/1024),
//				"original_pixel"=>$photo_img_original_info[0]."*".$photo_img_original_info[1],
//				"img_small"=>"http://".$server_name."/".$photo_img_small,
//				"img_detail"=>"http://".$server_name."/".$photo_img_detail,
//				"img_original"=>"http://".$server_name."/".$photo_img_original,
//				"img_big"=>"http://".$server_name."/".$photo_img_big,
//				"ip"=>$user_ip,
//				"upload_name"=>$_REQUEST["operator_name"]
//			);
            $result['error'] = false;
            if (!file_exists($imgaddr_small)) {
                $result['error'] = true;
                $result['error_img'] = '小图失败';//$this->ld['thumbnail_generate_failed'];
            }
            if (!file_exists($imgaddr_detail)) {
                $result['error'] = true;
                $result['error_img'] = '详细图失败';//$this->ld['detail_image_generate_failed'];
            }
            if (!file_exists($imgaddr_original)) {
                $result['error'] = true;
                $result['error_img'] = '原图失败';//$this->ld['original_image_build_failure'];
            }
            if (!file_exists($imgaddr_big)) {
                $result['error'] = true;
                $result['error_img'] = '大图失败';//$this->ld['big_image_generate_failure'];
            }
            if ($result['error'] == false) {
                //$now_table->saveAll($shop_gallery);
            //	$now_table->save($shop_gallery,array("conditions"=>array("id"=>$id)));
            } else {
                // @todo 删除垃圾文件 @unlink
            }
            //返回刚上传的内容图片
            if ($retain_original_image_when_upload_products == 1) {
                $result['img'] = $photo_category_galleries;
            } elseif ($retain_original_image_when_upload_products == 0) {
                @unlink($img_address);
            }
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($result));
            exit();
        }
    }

    //加水印
    public function add_image_water()
    {
        $watermark_location = $_REQUEST['wl'];
        $watermark_transparency = $_REQUEST['wt'];
        $WaterMark_img_address = WWW_ROOT.$_REQUEST['wf'];
        $img_original = WWW_ROOT.$_REQUEST['img_original'];
        $water_text_size = $_REQUEST['size'];
        $water_text_color = $_REQUEST['color'];
        $water_text = $_REQUEST['text'];
        $water_text_font = $_REQUEST['font'];
        if ($_REQUEST['type'] == 1) {
            $aa = $this->imageWaterMark($img_original, $watermark_location, $WaterMark_img_address, $watermark_transparency);
        }
        if ($_REQUEST['type'] == 2) {
            $this->imageWaterMark($img_original, $watermark_location, '', 50, $water_text_size, $water_text_color, $water_text, '../vendors/securimage/'.$water_text_font);
        }
        pr($aa);
        die;
    }

    /**
     * 创建图片的缩略图.
     *
     * @param string $img          原始图片的路径
     * @param int    $thumb_width  缩略图宽度
     * @param int    $thumb_height 缩略图高度
     * @param int    $filename     图片名..
     * @param strint $dir          指定生成图片的目录名
     *
     * @return mix 如果成功返回缩略图的路径，失败则返回false
     */
    public function make_thumb($img, $thumb_width = 0, $thumb_height = 0, $bgcolor = '#FFFFFF', $filename, $dir, $imgname)
    {
        //echo $filename;
        /* 检查缩略图宽度和高度是否合法 */
        if ($thumb_width == 0 && $thumb_height == 0) {
            return false;
        }
        /* 检查原始文件是否存在及获得原始文件的信息 */
        $org_info = @getimagesize($img);
        if (!$org_info) {
            return false;
        }

        $img_org = $this->img_resource($img, $org_info[2]);
        /* 原始图片以及缩略图的尺寸比例 */
        $scale_org = $org_info[0] / $org_info[1];
        /* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
        if ($thumb_width == 0) {
            $thumb_width = $thumb_height * $scale_org;
        }
        if ($thumb_height == 0) {
            $thumb_height = $thumb_width / $scale_org;
        }

        /* 创建缩略图的标志符 */
        $img_thumb = @imagecreatetruecolor($thumb_width, $thumb_height);//真彩

        /* 背景颜色 */

        if (empty($bgcolor)) {
            $bgcolor = $bgcolor;
        }
        $bgcolor = trim($bgcolor, '#');
        sscanf($bgcolor, '%2x%2x%2x', $red, $green, $blue);
        $clr = imagecolorallocate($img_thumb, $red, $green, $blue);
        imagefilledrectangle($img_thumb, 0, 0, $thumb_width, $thumb_height, $clr);

        if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height) {
            $lessen_width = $thumb_width;
            $lessen_height = $thumb_width / $scale_org;
        } else {
            /* 原始图片比较高，则以高度为准 */
            $lessen_width = $thumb_height * $scale_org;
            $lessen_height = $thumb_height;
        }
        $dst_x = ($thumb_width  - $lessen_width)  / 2;
        $dst_y = ($thumb_height - $lessen_height) / 2;

        /* 将原始图片进行缩放处理 */
        imagecopyresampled($img_thumb, $img_org, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $org_info[0], $org_info[1]);
        /* 生成文件 */
        if (function_exists('imagejpeg')) {
            $filename .= '.'.$imgname;
            imagejpeg($img_thumb, $dir.$filename, 100);
        } elseif (function_exists('imagegif')) {
            $filename .= '.'.imgname;
            imagegif($img_thumb, $dir.$filename, 100);
        } elseif (function_exists('imagepng')) {
            $filename .= '.'.$imgname;
            imagepng($img_thumb, $dir.$filename, 100);
        } else {
            return false;
        }
        imagedestroy($img_thumb);
        imagedestroy($img_org);
        //确认文件是否生成
        if (file_exists($dir.$filename)) {
            return  $filename;
        } else {
            return false;
        }
    }

    /*
    * 参数：
    *       $groundImage     	要加水印的图片
    *       $waterPos        	水印位置		0为随机位置；
    *                       	1为顶端居左，	2为顶端居中，	3为顶端居右；
    *                       	4为中部居左，	5为中部居中，	6为中部居右；
    *                       	7为底端居左，	8为底端居中，	9为底端居右；
    *       $waterImage      	图片水印
    *		$watermark_alpha	图片水印透明度
    *       $waterText       	文字水印
    *       $fontSize        	文字大小
    *       $textColor       	文字颜色
    *       $fontfile        	windows字体文件
    *       $xOffset         	水平偏移量，即在默认水印坐标值基础上加上这个值，默认为0，如果给水印留
    *                       	出水平方向上的边距，可以设置这个值,如：2 则表示在默认的基础上向右移2个单位,-2 表示向左移两单位
    *       $yOffset         	垂直偏移量，即在默认水印坐标值基础上加上这个值，默认为0，如果给水印留
    *                       	出垂直方向上的边距，可以设置这个值,如：2 则表示在默认的基础上向下移2个单位,-2 表示向上移两单位
    * 返回值：
    *        0   水印成功
    *        1   水印图片格式不支持
    *        2   要水印的背景图片不存在
    *        3   需要加水印的图片的长度或宽度比水印图片或文字区域还小，无法生成水印
    *        4   字体文件不存在
    *        5   水印文字颜色格式不正确
    *        6   水印背景图片格式目前不支持
    */
    public function imageWaterMark($groundImage = '', $waterPos = 0, $waterImage = '', $watermark_alpha = 50, $fontSize = 44, $textColor = '#6AD267', $waterText = '', $fontfile = '../vendors/securimage/elephant.ttf', $xOffset = 0, $yOffset = 0)
    {
        $fontfile = WWW_ROOT.'app/vendors/securimage/msyh.ttf';
        $isWaterImage = false;
        //读取水印文件
        if (!empty($waterImage) && file_exists($waterImage)) {
            $isWaterImage = true;
            $water_info = getimagesize($waterImage);
            $water_w = $water_info[0];//取得水印图片的宽
            $water_h = $water_info[1];//取得水印图片的高
            switch ($water_info[2]) {    //取得水印图片的格式  
                case 1:$water_im = imagecreatefromgif($waterImage);break;
                case 2:$water_im = imagecreatefromjpeg($waterImage);break;
                case 3:$water_im = imagecreatefrompng($waterImage);break;
                default:return 1;
            }
        }
         //读取背景图片
        if (!empty($groundImage) && file_exists($groundImage)) {
            $ground_info = getimagesize($groundImage);
            $ground_w = $ground_info[0];//取得背景图片的宽
            $ground_h = $ground_info[1];//取得背景图片的高
            switch ($ground_info[2]) {    //取得背景图片的格式  
                 case 1:$ground_im = imagecreatefromgif($groundImage);break;
                 case 2:$ground_im = imagecreatefromjpeg($groundImage);break;
                 case 3:$ground_im = imagecreatefrompng($groundImage);break;
                 default:return 1;
             }
        } else {
            return 2;
        }
         //水印位置
        if ($isWaterImage) { //图片水印
             $w = $water_w;
            $h = $water_h;
            $label = '图片的';
        } else {
            //文字水印
            if (!file_exists($fontfile)) {
                return 4;
            }
            $temp = imagettfbbox($fontSize, 0, $fontfile, $waterText);//取得使用 TrueType 字体的文本的范围
                $w = $temp[2] - $temp[6];
            $h = $temp[3] - $temp[7];
            unset($temp);
        }
        if (($ground_w < $w) || ($ground_h < $h)) {
            return 3;
        }
        switch ($waterPos) {
             case 0://随机
                 $posX = rand(0, ($ground_w - $w));
                 $posY = rand(0, ($ground_h - $h));
                 break;
             case 1://1为顶端居左
                 $posX = 0;
                 $posY = 0;
                 break;
             case 2://2为顶端居中
                 $posX = ($ground_w - $w) / 2;
                 $posY = 0;
                 break;
             case 3://3为顶端居右
                 $posX = $ground_w - $w;
                 $posY = 0;
                 break;
             case 4://4为中部居左
                 $posX = 0;
                 $posY = ($ground_h - $h) / 2;
                 break;
             case 5://5为中部居中
                 $posX = ($ground_w - $w) / 2;
                 $posY = ($ground_h - $h) / 2;
                 break;
             case 6://6为中部居右
                 $posX = $ground_w - $w;
                 $posY = ($ground_h - $h) / 2;
                 break;
             case 7://7为底端居左
                 $posX = 0;
                 $posY = $ground_h - $h;
                 break;
             case 8://8为底端居中
                 $posX = ($ground_w - $w) / 2;
                 $posY = $ground_h - $h;
                 break;
             case 9://9为底端居右
                 $posX = $ground_w - $w;
                 $posY = $ground_h - $h;
                 break;
             default://随机
                 $posX = rand(0, ($ground_w - $w));
                 $posY = rand(0, ($ground_h - $h));
                 break;
         }
         //设定图像的混色模式
         imagealphablending($ground_im, true);
        if ($isWaterImage) { //图片水印
             imagecopymerge($ground_im, $water_im, $posX + $xOffset, $posY + $yOffset, 0, 0, $water_w, $water_h, $watermark_alpha);//拷贝水印到目标文件
        } else {
            //文字水印
            if (!empty($textColor) && (strlen($textColor) == 7)) {
                $R = hexdec(substr($textColor, 1, 2));
                $G = hexdec(substr($textColor, 3, 2));
                $B = hexdec(substr($textColor, 5));
            } else {
                return 5;
            }
            $a = imagettftext($ground_im, $fontSize, 0, $posX + $xOffset, $posY + $h + $yOffset, imagecolorallocate($ground_im, $R, $G, $B), $fontfile, $waterText);
        }
         //生成水印后的图片
         @unlink($groundImage);
        switch ($ground_info[2]) {//取得背景图片的格式
            case 1:imagegif($ground_im, $groundImage);break;
            case 2:imagejpeg($ground_im, $groundImage);break;
            case 3:imagepng($ground_im, $groundImage);break;
            default: return 6;
         }
         //释放内存
         if (isset($water_info)) {
             unset($water_info);
         }
        if (isset($water_im)) {
            imagedestroy($water_im);
        }
        unset($ground_info);
        imagedestroy($ground_im);

        return 0;
        die;
    }

    /**
     * 根据来源文件的文件类型创建一个图像操作的标识符.
     *
     * @param string $img_file  图片文件的路径
     * @param string $mime_type 图片文件的文件类型
     *
     * @return resource 如果成功则返回图像操作标志符，反之则返回错误代码
     */
    public function img_resource($img_file, $mime_type)
    {
        switch ($mime_type) {

            case 1:
            case 'image/gif':
            $res = imagecreatefromgif($img_file);
            break;

            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
            $res = imagecreatefromjpeg($img_file);
            break;

            case 3:
            case 'image/x-png':
            case 'image/png':
            $res = imagecreatefrompng($img_file);
            break;

            default:
            return false;
        }

        return $res;
    }

    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }
    
    function rebuild_pictures($id=0){
    		Configure::write('debug', 0);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['flag']="0";
    		$id_arr = explode('-', $id);
    		$image_infos=$this->PhotoCategoryGallery->find('all',array('conditions'=>array('PhotoCategoryGallery.id'=>$id_arr)));
    		if(!empty($image_infos)){
    			$photocategory_info=$this->PhotoCategory->find('all');
    			$photocategory_data=array();
    			foreach($photocategory_info as $v){
    				$photocategory_data[$v['PhotoCategory']['id']]=$v['PhotoCategory'];
    			}
    			$default_small_img_height = (isset($this->configs['small_img_height']) && $this->configs['small_img_height'] > 0) ? $this->configs['small_img_height'] : 140;
        		$default_small_img_width = (isset($this->configs['small_img_width']) && $this->configs['small_img_width'] > 0) ? $this->configs['small_img_width'] : 140;
        		$default_mid_img_height = (isset($this->configs['mid_img_height']) && $this->configs['mid_img_height'] > 0) ? $this->configs['mid_img_height'] : 400;
        		$default_mid_img_width = (isset($this->configs['mid_img_width']) && $this->configs['mid_img_width'] > 0) ? $this->configs['mid_img_width'] : 400;
        		$default_big_img_height = (isset($this->configs['big_img_height']) && $this->configs['big_img_height'] > 0) ? $this->configs['big_img_height'] : 800;
        		$default_big_img_width = (isset($this->configs['big_img_width']) && $this->configs['big_img_width'] > 0) ? $this->configs['big_img_width'] : 800;
	    		foreach($image_infos as $v){
	    			$img_data=$v['PhotoCategoryGallery'];
	    			$photo_category_id=isset($img_data['photo_category_id'])?$img_data['photo_category_id']:0;
	    			$small_img_width=isset($photocategory_data[$photo_category_id]['cat_small_img_width'])?$photocategory_data[$photo_category_id]['cat_small_img_width']:$default_small_img_width;
	    			$small_img_height=isset($photocategory_data[$photo_category_id]['cat_small_img_height'])?$photocategory_data[$photo_category_id]['cat_small_img_height']:$default_small_img_height;
	    			$big_img_width=isset($photocategory_data[$photo_category_id]['cat_big_img_width'])?$photocategory_data[$photo_category_id]['cat_big_img_width']:$default_big_img_width;
	    			$big_img_height=isset($photocategory_data[$photo_category_id]['cat_big_img_height'])?$photocategory_data[$photo_category_id]['cat_big_img_height']:$default_big_img_height;
	    			$mid_img_width=isset($photocategory_data[$photo_category_id]['cat_mid_img_width'])?$photocategory_data[$photo_category_id]['cat_mid_img_width']:$default_mid_img_width;
	    			$mid_img_height=isset($photocategory_data[$photo_category_id]['cat_mid_img_height'])?$photocategory_data[$photo_category_id]['cat_mid_img_height']:$default_mid_img_height;
	    			
//	    			pr($small_img_width."x".$small_img_height);
//	    			pr($big_img_width."x".$big_img_height);
//	    			pr($mid_img_width."x".$mid_img_height);
	    			
	    			$imgaddr_original = WWW_ROOT.$img_data['img_original'];
				$old_name = $imgaddr_original;
				$photoo_name = explode('/', $old_name);
				$total = sizeof($photoo_name);
				$k = $total - 1;
				$image_name = $photoo_name[$k];
				$imgname_arr = explode('.', $image_name);
				//绝对路径
				$imgaddr_small = str_replace('original', 'small', $imgaddr_original);
				$imgaddr_thumb = str_replace('original', 'small', $imgaddr_original);
				$imgaddr_detail = str_replace('original', 'detail', $imgaddr_original);
				$imgaddr_big = str_replace('original', 'big', $imgaddr_original);
				
				$thumb = str_replace($image_name, '', $imgaddr_thumb);
				$img_detail = str_replace($image_name, '', $imgaddr_detail);
				$big = str_replace($image_name, '', $imgaddr_big);
				
				$this->make_thumb($imgaddr_original, $small_img_width, $small_img_height, '#FFFFFF', $imgname_arr[0], $thumb, $imgname_arr[1]);
				$this->make_thumb($imgaddr_original, $mid_img_width, $mid_img_height, '#FFFFFF', $imgname_arr[0], $img_detail, $imgname_arr[1]);
				$this->make_thumb($imgaddr_original, $big_img_width, $big_img_height, '#FFFFFF', $imgname_arr[0], $big, $imgname_arr[1]);
	    		}
	    		$result['flag']="1";
	    		$result['message']=sprintf($this->ld['operator_successful'], count($image_infos));
    		}else{
    			$result['message']="not found data";
    		}
		die(json_encode($result));
    }
}
