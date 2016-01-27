<?php

/**
 * 商品类型属性模型.
 */
class attribute extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Attribute 商品属性模型
     */
    public $name = 'Attribute';

    /*
     * @var $hasOne array 关联商品类型多语言表
     */
    public $hasOne = array('AttributeI18n' => array('className' => 'AttributeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'attribute_id',
                             ),
                  );

    public $hasMany = array(
                        'AttributeOption' => array(
                        'className' => 'AttributeOption',
                        'conditions' => '',
                        'order' => 'AttributeOption.attribute_id',
                        'fields' => 'AttributeOption.*',
                        'dependent' => true,
                        'foreignKey' => 'attribute_id',
                    ),
        );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " AttributeI18n.locale = '".$locale."'";
        $this->hasOne['AttributeI18n']['conditions'] = $conditions;
        $this->hasMany['AttributeOption']['conditions'] = "AttributeOption.locale = '".$locale."'";
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 属性ID
     *
     * @return array $lists_formated 返回类型树
     */
    public function localeformat($id)
    {
        $product_type_attribute_data = $this->find('all', array('conditions' => array('Attribute.id' => $id)));
        $product_type_attribute_data_formated = array();
        foreach ($product_type_attribute_data as $k => $v) {
            $product_type_attribute_data_formated['Attribute'] = $v['Attribute'];
            $product_type_attribute_data_formated['AttributeI18n'][] = $v['AttributeI18n'];
            $product_type_attribute_data_formated['AttributeOption'] = $v['AttributeOption'];
            foreach ($product_type_attribute_data_formated['AttributeI18n'] as $key => $val) {
                $product_type_attribute_data_formated['AttributeI18n'][$val['locale']] = $val;
            }
            foreach ($product_type_attribute_data_formated['AttributeOption'] as $key => $val) {
                $product_type_attribute_data_formated['AttributeOption'][$val['locale']] = $val;
            }
        }

        return $product_type_attribute_data_formated;
    }

    /**
     * get_attr_list方法，查询属性值及商品的属性值.
     *
     * @param int $cat_id     类型ID
     * @param int $product_id 商品ID
     *
     * @return array $lists 返回属性值及商品的属性值
     */
    public function get_attr_list($attr_ids, $product_id = 0, $locale = 'chi')
    {
        $ProductAttribute = ClassRegistry::init('ProductAttribute');
        $this->hasOne = array();
        $this->hasMany = array('AttributeI18n' => array('className' => 'AttributeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'attribute_id',
                        ),
                        'ProductAttribute' => array(
                                              'className' => 'ProductAttribute',
                                              'conditions' => 'ProductAttribute.product_id='.$product_id,
                                              'dependent' => true,
                                              'order' => 'ProductAttribute.id asc,ProductAttribute.locale desc',
                                              'foreignKey' => 'attribute_id',
                                             ),
                        'AttributeOption' => array(
                        'className' => 'AttributeOption',
                        'conditions' => '',
                        'order' => 'AttributeOption.attribute_id',
                        'fields' => 'AttributeOption.*',
                        'dependent' => true,
                        'foreignKey' => 'attribute_id', ),
        );
          //查询属性值及商品的属性值
          $condition['Attribute.status'] = '1';
        $condition['Attribute.id'] = $attr_ids;
        $lists = $this->find('all', array('conditions' => $condition, 'order' => 'Attribute.id'));
        foreach ($lists as $k => $v) {
            foreach ($v['AttributeI18n'] as $vv) {
                $lists[$k]['Attribute']['locale'][$vv['locale']]['attr_value'] = $vv['attr_value'];
                $lists[$k]['Attribute']['locale'][$vv['locale']]['default_value'] = $vv['default_value'];
            }
        }
        foreach ($lists as $k => $v) {
            foreach ($v['AttributeI18n'] as $kk => $vv) {
                if ($vv['locale'] == $locale) {
                    $lists[$k]['AttributeI18n'] = $vv;
                }
            }
        }

        return $lists;
    }

    /**
     * 取得通用属性和某分类的属性，以及某图片的属性值.
     *
     * @param int $cat_id      分类编号
     * @param int $products_id 图片编号
     *
     * @return array 规格与属性列表
     */
    public function build_attr_html($cat_id, $product_id = 0)
    {
        $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
        $attr_ids = $ProductTypeAttribute->getattrids(array($cat_id, 0));
        $this->hasOne['ProductAttribute'] = array(
                      'className' => 'ProductAttribute',
                      'conditions' => 'ProductAttribute.product_id='.$product_id,
                      'dependent' => true,
                      'foreignKey' => 'attribute_id',
                    );
        $this->bindModel(array('hasOne' => $this->hasOne));
        $attr = $this->get_attr_list($attr_ids, $product_id, $this->locale);
        $html = '';
        $spec = 0;
        $j = 0;
        $Language = ClassRegistry::init('Language');
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $Language->getinfo();
        $this->backend_locales = $Language->info['backend_locales'];
        $this->front_locales = $Language->info['front_locales'];
        $lan_count = sizeof($this->backend_locales);
        foreach ($attr as $key => $val) {
            if (!empty($val['ProductAttribute'])) {
                if ($lan_count != sizeof($val['ProductAttribute'])) {
                    $data_log_locale = array();
                    foreach ($val['ProductAttribute'] as $kk => $vv) {
                        $data_log_locale[] = $vv['locale'];
                    }
                    foreach ($this->front_locales as $k => $v) {
                        if (!in_array($v['Language']['locale'], $data_log_locale)) {
                            $data_log = $val['ProductAttribute'][0];
                            unset($data_log['created']);
                            unset($data_log['modified']);
                            $data_log['locale'] = $v['Language']['locale'];
                            $data_log['product_type_attribute_value'] = '';
                            $attr[$key]['ProductAttribute'][] = $data_log;
                        }
                    }
                }
            }
        }
        foreach ($attr as $key => $val) {
            if ($val['Attribute']['type'] == 'customize') {
                continue;
            }
            $attr_name = $val['AttributeI18n']['name'];
            $html .= <<<EOT
			<table>
			<tr>
			<td style="width:150px;padding-left:22px;">$attr_name</td><td style="padding:0">
EOT;
            $i = 0;
            $upload = true;
            if (!empty($val['ProductAttribute'])) {
                foreach ($val['ProductAttribute'] as $kk => $vv) {
                    if ($i == 0 || $i == $lan_count) {
                        $i = 0;
                        $table = 'attr_table_'.$val['Attribute']['id'].'_'.++$j;
                        $html .= '<table id='.$table.' name='.$table.'>';
                        $html .= '<tbody>';
                    }
                    foreach ($this->front_locales as $k => $v) {
                        $k_locale = $vv['locale'];
                        if ($vv['locale'] == $v['Language']['locale']) {
                            $val['ProductAttribute']['attribute_value'] = empty($vv['attribute_value']) ? $val['Attribute']['locale'][$v['Language']['locale']]['default_value'] : $vv['attribute_value'];
                            $val['ProductAttribute']['attribute_image_path'] = empty($vv['attribute_image_path']) ? '' : $vv['attribute_image_path'];
                            $val['ProductAttribute']['attribute_back_image_path'] = empty($vv['attribute_back_image_path']) ? '' : $vv['attribute_back_image_path'];
                            $val['ProductAttribute']['attribute_related_image_path'] = empty($vv['attribute_related_image_path']) ? '' : $vv['attribute_related_image_path'];
                            $val['ProductAttribute']['attribute_related_back_image_path'] = empty($vv['attribute_related_back_image_path']) ? '' : $vv['attribute_related_back_image_path'];
                            $val['ProductAttribute']['attribute_price'] = $vv['attribute_price'];
                            $val['ProductAttribute']['orderby'] = $vv['orderby'];
                            $upload = false;
//							if($kk==0){
//								$vv['id']='';
//							}
                            $html .= "<tr><td><input  type='hidden' name='attr_id_list[".$vv['id'].']['.$k_locale."]' value=".$val['Attribute']['id']." /><input  type='hidden' name='attr_locale_list[".$vv['id'].']['.$k_locale."]' value=".$v['Language']['locale'].' />';
//							if($kk==0){
//								$html .="<tr><td><input  type='hidden' name='attr_id_list[]' value=".$val['Attribute']['id']." /><input  type='hidden' name='attr_locale_list[]' value=".$v['Language']['locale']." />";
//							}else{
//				            	$html .="<tr><td><input  type='hidden' name='attr_id_list[".$vv['id']."]' value=".$val['Attribute']['id']." /><input  type='hidden' name='attr_locale_list[".$vv['id']."]' value=".$v['Language']['locale']." />";
//				            }
                            if ($val['Attribute']['attr_input_type'] == '0' || $val['Attribute']['attr_input_type'] == '4') {
                                $html .= '<input name="attr_value_list['.$vv['id'].']['.$k_locale.']" type="text" class="input_text" value="'.$val['ProductAttribute']['attribute_value'].'"  /> ';
//				            	if($kk==0){
//				                	$html .= '<input name="attr_value_list[]" type="text" class="input_text" value="'.$val['ProductAttribute']['attribute_value'].'"  /> ';
//				            	}else{
//				                	$html .= '<input name="attr_value_list['.$vv['id'].']" type="text" class="input_text" value="'.$val['ProductAttribute']['attribute_value'].'"  /> ';
//					            }
                            } elseif ($val['Attribute']['attr_input_type'] == '2') {
                                $html .= '<textarea name="attr_value_list['.$vv['id'].']['.$k_locale.']" >'.$val['ProductAttribute']['attribute_value'].'</textarea>';
//				            	if($kk==0){
//				                	$html .= '<textarea name="attr_value_list[]" >'.$val['ProductAttribute']['attribute_value'].'</textarea>';
//				            	}else{
//				                	$html .= '<textarea name="attr_value_list['.$vv['id'].']" >'.$val['ProductAttribute']['attribute_value'].'</textarea>';
//					            }
                            } elseif ($val['Attribute']['attr_input_type'] == '3') {
                                $upload = false;
                                $value = isset($val['ProductAttribute']['attribute_value']) && !empty($val['ProductAttribute']['attribute_value']) ? $val['ProductAttribute']['attribute_value'] : 'jpg,png,gif:500';
                                $value = explode(':', $value);
                                $value[0] = isset($value[0]) && !empty($value[0]) ? $value[0] : 'jpg,png,gif';
                                $value[1] = isset($value[1]) && !empty($value[1]) ? $value[1] : '500';
                                $html .= $this->ld['prod_type_format'].'<input name="attr_value_list['.$vv['id'].']['.$k_locale.']" type="text" class="input_text" value="'.$value[0].'"/> '.$this->ld['prod_type_format_require'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->ld['prod_type_format_size'].'<input name="attr_value_upload_size['.$vv['id'].']['.$k_locale.']" type="text" class="input_text" value="'.$value[1].'"/>(****KB)';
//				            	if($kk==0){
//							    	$html .= $this->ld['prod_type_format'].'<input name="attr_value_list[]" type="text" class="input_text" value="'.$value[0].'"/> '.$this->ld['prod_type_format_require'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->ld['prod_type_format_size'].'<input name="attr_value_upload_size[]" type="text" class="input_text" value="'.$value[1].'"/>(****KB)';
//				            	}else{
//							    	$html .= $this->ld['prod_type_format'].'<input name="attr_value_list['.$vv['id'].']" type="text" class="input_text" value="'.$value[0].'"/> '.$this->ld['prod_type_format_require'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->ld['prod_type_format_size'].'<input name="attr_value_upload_size['.$vv['id'].']" type="text" class="input_text" value="'.$value[1].'"/>(****KB)';
//					            }
                            } else {
                                $html .= '<select name="attr_value_list['.$vv['id'].']['.$k_locale.']" >';
//    	           				if($kk==0){
//				                	$html .= '<select name="attr_value_list[]" >';
//				            	}else{
//				                	$html .= '<select name="attr_value_list['.$vv['id'].']" >';
//					            }
                                $html .= '<option value="">';
                                $html .= $this->ld['please_select'];
                                $html .= '</option>';
                                if (isset($val['Attribute']['locale'][$v['Language']['locale']]['attr_value'])) {
                                    $attr_values = explode("\n", $val['Attribute']['locale'][$v['Language']['locale']]['attr_value']);
                                    foreach ($attr_values as $opt) {
                                        $opt = trim($opt);
                                        $html .= ($val['ProductAttribute']['attribute_value'] != $opt) ? '<option value="'.$opt.'">'.$opt.'</option>' : '<option value="'.$opt.'" selected="selected">'.$opt.'</option>';
                                    }
                                }
                                $html .= '</select> ';
                                $val['ProductAttribute']['attribute_color_css'] = empty($vv['attribute_color_css']) ? '' : $vv['attribute_color_css'];
                                $val['ProductAttribute']['attribute_shell_num'] = empty($vv['attribute_shell_num']) ? '' : $vv['attribute_shell_num'];
                                if (isset($val['Attribute']['code']) && $val['Attribute']['code'] == 'waikecolor') {
                                    $html .= $this->ld['color_css'].'<input name="attr_color_css_list['.$vv['id'].']['.$k_locale.']" type="text" class="input_text" value="'.$val['ProductAttribute']['attribute_color_css'].'"  /> ';
                                }
                                if (isset($val['Attribute']['code']) && $val['Attribute']['code'] == 'waikemoban') {
                                    $html .= $this->ld['number_template'].'<input name="attr_shell_num_list['.$vv['id'].']['.$k_locale.']" type="text" class="input_text" value="'.$val['ProductAttribute']['attribute_shell_num'].'"  /> ';
                                }
                            }
                            $html .= '<span>';
                            //$html .= ($val['Attribute']['attr_type']=="1") ? $this->ld['attributes_price'].'<input type="text" name="attr_price_list[]" value="'.$val['ProductAttribute']['attribute_price'].'" size="2" maxlength="10" />': ' <input type="hidden" name="attr_price_list[]" value="0" />';
                            $html .= ($val['Attribute']['type'] == 'buy') ? $this->ld['attributes_price'].'<input type="text" name="attr_price_list['.$vv['id'].']['.$k_locale.']" value="'.$val['ProductAttribute']['attribute_price'].'" size="10" maxlength="10" />' : ' <input type="hidden" name="attr_price_list['.$vv['id'].']['.$k_locale.']" value="0" />';
                            $html .= '</span><span>';
                            $html .= $this->ld['sort']."<input type='text' size='4' name='attr_orderby_list[".$vv['id'].']['.$k_locale."]' value='".$val['ProductAttribute']['orderby']."'>";
                            if ($val['Attribute']['attr_input_type'] != '3' && $val['Attribute']['code'] != 'pencilcolor' && ($val['Attribute']['code'] == 'waikecolor' || $val['Attribute']['code'] == 'waikemoban')) {
                                $html .= "</span><span class='lang'>".$this->ld[$v['Language']['locale ']].'</span><ul>';
                                if (isset($val['ProductAttribute']['attribute_image_path'])) {
                                    $img_thumb_format = explode('http://', $val['ProductAttribute']['attribute_image_path']);
                                    if (isset($img_thumb_format) && count($img_thumb_format) == 1) {
                                        $val['ProductAttribute']['attribute_image_path'] = IMG_HOST.$val['ProductAttribute']['attribute_image_path'];
                                    }
                                }
                                if (IMG_HOST == $val['ProductAttribute']['attribute_image_path']) {
                                    $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'1:</strong><input type="text" class="input_text" name="attr_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_image_path_list['.$vv['id'].']['.$k_locale.']" value="" />';
                                } else {
                                    $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'1:</strong><input type="text" class="input_text" name="attr_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_image_path_list['.$vv['id'].']['.$k_locale.']" value="'.$val['ProductAttribute']['attribute_image_path'].'" />';
                                }
                                if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                    $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_image_path_list['.$vv['id'].']['.$k_locale.']\')"  />';
                                    $html .= '<div class="img_select">';
                                    if ($val['ProductAttribute']['attribute_image_path'] == '') {
                                        $html .= '<span>'.'No Picture'.'</span>';
                                    } else {
                                        $html .= '<img id="show_attr_image_path_list['.$vv['id'].']['.$k_locale.']" alt="" src="'.$val['ProductAttribute']['attribute_image_path'].'"></div></li>';
                                    }
                                }
                                if (isset($val['Attribute']['code']) && $val['Attribute']['code'] != 'waikecolor') {
                                    if (isset($val['Attribute']['code']) && $val['Attribute']['code'] != 'waikemoban') {
                                        if (isset($val['ProductAttribute']['attribute_back_image_path'])) {
                                            $img_thumb_format = explode('http://', $val['ProductAttribute']['attribute_back_image_path']);
                                            if (isset($img_thumb_format) && count($img_thumb_format) == 1) {
                                                $val['ProductAttribute']['attribute_back_image_path'] = IMG_HOST.$val['ProductAttribute']['attribute_back_image_path'];
                                            }
                                        }
                                        if (IMG_HOST == $val['ProductAttribute']['attribute_back_image_path']) {
                                            $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'2:</strong><input type="text" class="input_text" name="attr_back_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_back_image_path_list['.$vv['id'].']['.$k_locale.']" value="" />';
                                        } else {
                                            $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'2:</strong><input type="text" class="input_text" name="attr_back_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_back_image_path_list['.$vv['id'].']['.$k_locale.']" value="'.$val['ProductAttribute']['attribute_back_image_path'].'" />';
                                        }
                                        if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                            $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_back_image_path_list['.$vv['id'].']['.$k_locale.']\')"  />';
                                            $html .= '<div class="img_select">';
                                            if ($val['ProductAttribute']['attribute_back_image_path'] == '') {
                                                $html .= '<span>'.'No Picture'.'</span>';
                                            } else {
                                                $html .= '<img id="show_attr_back_image_path_list['.$vv['id'].']['.$k_locale.']" alt="" src="'.$val['ProductAttribute']['attribute_back_image_path'].'"></div></li>';
                                            }
                                        }
                                        if (isset($val['ProductAttribute']['attribute_related_image_path'])) {
                                            $img_thumb_format = explode('http://', $val['ProductAttribute']['attribute_related_image_path']);
                                            if (isset($img_thumb_format) && count($img_thumb_format) == 1) {
                                                $val['ProductAttribute']['attribute_related_image_path'] = IMG_HOST.$val['ProductAttribute']['attribute_related_image_path'];
                                            }
                                        }
                                        if (IMG_HOST == $val['ProductAttribute']['attribute_related_image_path']) {
                                            $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'3:</strong><input type="text" class="input_text" name="attr_related_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_related_image_path_list['.$vv['id'].']['.$k_locale.']" value="" />';
                                        } else {
                                            $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'3:</strong><input type="text" class="input_text" name="attr_related_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_related_image_path_list['.$vv['id'].']['.$k_locale.']" value="'.$val['ProductAttribute']['attribute_related_image_path'].'" />';
                                        }
                                        if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                            $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_related_image_path_list['.$vv['id'].']['.$k_locale.']\')"  />';
                                            $html .= '<div class="img_select">';
                                            if ($val['ProductAttribute']['attribute_related_image_path'] == '') {
                                                $html .= '<span>'.'No Picture'.'</span>';
                                            } else {
                                                $html .= '<img id="show_attr_related_image_path_list['.$vv['id'].']['.$k_locale.']" alt="" src="'.$val['ProductAttribute']['attribute_related_image_path'].'"></div></li>';
                                            }
                                        }

                                        if (isset($val['ProductAttribute']['attribute_related_back_image_path'])) {
                                            $img_thumb_format = explode('http://', $val['ProductAttribute']['attribute_related_back_image_path']);
                                            if (isset($img_thumb_format) && count($img_thumb_format) == 1) {
                                                $val['ProductAttribute']['attribute_related_back_image_path'] = IMG_HOST.$val['ProductAttribute']['attribute_related_back_image_path'];
                                            }
                                        }
                                        if (IMG_HOST == $val['ProductAttribute']['attribute_related_back_image_path']) {
                                            $html .= '<li id="attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']"><strong>'.$this->ld['prod_type_image_parameters'].'4:</strong><input type="text" class="input_text" name="attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']" value="" />';
                                        } else {
                                            $html .= '<li id="attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']"><strong>'.$this->ld['prod_type_image_parameters'].'4:</strong><input type="text" class="input_text" name="attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']" id="attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']" value="'.$val['ProductAttribute']['attribute_related_back_image_path'].'" />';
                                        }
                                        if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                            $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']\')"  />';
                                            $html .= '<div class="img_select">';
                                            if ($val['ProductAttribute']['attribute_related_back_image_path'] == '') {
                                                $html .= '<span>'.'No Picture'.'</span>';
                                            } else {
                                                $html .= '<img id="show_attr_related_back_image_path_list['.$vv['id'].']['.$k_locale.']" alt="" src="'.$val['ProductAttribute']['attribute_related_back_image_path'].'"></div></li>';
                                            }
                                        }
                                    }
                                }
                            } else {
                                $html .= '<span class="lang">'.$this->ld[$v['Language']['locale']].'</span>';
                            }
                            $html .= '</ul></td></tr>';
                        }
                        ++$i;
                    }
                    if ($i == $lan_count) {
                        $html .= '</tbody></table>';
                    }
                }
            }
            if ($upload) {
                $table = 'attr_table_'.$val['Attribute']['id'].'_'.++$j;
                $html .= '<table id='.$table.' name='.$table.'>';
                $html .= '<tbody>';
            /*
            $html .= "<tbody><tr><th rowspan='".(sizeof($this->front_locales)+1)."' style='width:50px'>";
            if($val['Attribute']['attr_type']=="1"){
                $html .= ($spec!=$val['Attribute']['id']) ? '<a href="javascript:;" onclick="addSpec(\''.$table.'\',\''.$key.'\')">[+]</a>': '<a href="javascript:;" onclick="addSpec(\''.$table.'\',\''.$key.'\')">[+]</a>';
                $spec=$val['Attribute']['id'];
            }
            $html .= "</th></tr>";
            */
            foreach ($this->front_locales as $k => $v) {
                $value = @$val['Attribute']['locale'][$v['Language']['locale']]['default_value'];
                $k_locale = $v['Language']['locale'];
                $price = '';
                $html .= "<tr><td><input  type='hidden' name='clone_attr_id_list[".$key.']['.$k_locale."]' value=".$val['Attribute']['id']." /><input  type='hidden' name='clone_attr_locale_list[".$key.']['.$k_locale."]' value=".$v['Language']['locale'].' />';
                if ($val['Attribute']['attr_input_type'] == '0' || $val['Attribute']['attr_input_type'] == '4') {
                    $html .= '<input name="clone_attr_value_list['.$key.']['.$k_locale.']" type="text" class="input_text" value="'.$value.'"  />';
                } elseif ($val['Attribute']['attr_input_type'] == '2') {
                    $html .= '<textarea name="clone_attr_value_list['.$key.']['.$k_locale.']" >'.$value.'</textarea>';
                } elseif ($val['Attribute']['attr_input_type'] == '3') {
                    $html .= $this->ld['prod_type_format'].'<input name="clone_attr_value_list['.$key.']['.$k_locale.']" type="text" class="input_text" value="jpg,png,gif"/> '.$this->ld['prod_type_format_require'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->ld['prod_type_format_size'].'<input name="clone_attr_value_upload_size['.$key.']['.$k_locale.']" type="text" class="input_text" value="500"/>(****KB)';
                } else {
                    $html .= '<select name="clone_attr_value_list['.$key.']['.$k_locale.']" >';
                    $html .= '<option value="">';
                    $html .= $this->ld['please_select'];
                    $html .= '</option>';
                    if (isset($val['Attribute']['locale'][$v['Language']['locale']]['attr_value'])) {
                        $attr_values = explode("\n", $val['Attribute']['locale'][$v['Language']['locale']]['attr_value']);
                        foreach ($attr_values as $opt) {
                            $opt = trim($opt);
                            $html .= ($value != $opt) ? '<option value="'.$opt.'">'.$opt.'</option>' : '<option value="'.$opt.'" selected="selected">'.$opt.'</option>';
                        }
                    }
                    $html .= '</select> ';
                    if (isset($val['Attribute']['code']) && $val['Attribute']['code'] == 'waikecolor') {
                        $html .= $this->ld['color_css'].'<input name="attr_color_css_list['.$key.']['.$k_locale.']" type="text" class="input_text" value=""  />';
                    }
                    if (isset($val['Attribute']['code']) && $val['Attribute']['code'] == 'waikemoban') {
                        $html .= $this->ld['number_template'].'<input name="attr_shell_num_list['.$key.']['.$k_locale.']" type="text" class="input_text" value=""  />';
                    }
                }
                $html .= '<span>';
                $html .= ($val['Attribute']['type'] == 'buy') ? $this->ld['attributes_price'].'<input type="text" name="clone_attr_price_list['.$key.']['.$k_locale.']" value="'.$price.'" size="10" maxlength="10" />' : ' <input type="hidden" name="attr_price_list['.$key.']['.$k_locale.']" value="0" />';
                $html .= '</span><span>';
                $html .= $this->ld['sort']."<input type='text' size='4' name='clone_attr_orderby_list[".$key.']['.$k_locale."]' value=''>";
                $html .= '</span><span class="lang">'.$this->ld[$v['Language']['locale']].'</span>';
            //   $html .= '</span><span class="lang">'.$this->ld[$v["Language"]["map"]].'</span></td></tr>';
                if ($val['Attribute']['attr_input_type'] != '3' && $val['Attribute']['code'] != 'pencilcolor' && ($val['Attribute']['code'] == 'waikecolor' || $val['Attribute']['code'] == 'waikemoban')) {
                    $html .= '<ul><li><strong>'.$this->ld['prod_type_image_parameters'].'1:</strong><input type="text" class="input_text" name="attr_image_path_list['.$key.']['.$k_locale.']" id="attr_image_path_list['.$key.']['.$k_locale.']" value="" />';
                    if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                        $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_image_path_list['.$key.']['.$k_locale.']\')"  />';
                        $html .= '<div class="img_select">';
                        $html .= '<img id="show_attr_image_path_list['.$key.']['.$k_locale.']" alt="" src=""></div></li>';
                    }
                    if (isset($val['Attribute']['code']) && $val['Attribute']['code'] != 'waikecolor') {
                        if (isset($val['Attribute']['code']) && $val['Attribute']['code'] != 'waikemoban') {
                            $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'2:</strong><input type="text" class="input_text" name="attr_back_image_path_list['.$key.']['.$k_locale.']" id="attr_back_image_path_list['.$key.']['.$k_locale.']" value="" />';
                            if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_back_image_path_list['.$key.']['.$k_locale.']\')"  />';
                                $html .= '<div class="img_select">';
                                $html .= '<img id="show_attr_back_image_path_list['.$key.']['.$k_locale.']" alt="" src=""></div></li>';
                            }
                            $html .= '</ul><ul><li><strong>'.$this->ld['prod_type_image_parameters'].'3:</strong><input type="text" class="input_text" name="attr_related_image_path_list['.$key.']['.$k_locale.']" id="attr_related_image_path_list['.$key.']['.$k_locale.']" value="" />';
                            if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_related_image_path_list['.$key.']['.$k_locale.']\')"  />';
                                $html .= '<div class="img_select">';
                                $html .= '<img id="show_attr_related_image_path_list['.$key.']['.$k_locale.']" alt="" src=""></div></li>';
                            }
                            $html .= '<li><strong>'.$this->ld['prod_type_image_parameters'].'4:</strong><input type="text" class="input_text" name="attr_related_back_image_path_list['.$key.']['.$k_locale.']" id="attr_related_back_image_path_list['.$key.']['.$k_locale.']" value="" />';

                            if (isset($this->configs['Product_Open_uploaded']) && $this->configs['Product_Open_uploaded'] == 1) {
                                $html .= '<input type="button"  value="'.$this->ld['choose_picture'].'"  onclick="select_img(\'attr_related_back_image_path_list['.$key.']['.$k_locale.']\')"  />';
                                $html .= '<div class="img_select">';
                                $html .= '<img id="show_attr_related_back_image_path_list['.$key.']['.$k_locale.']" alt="" src=""></div></li>';
                            }
                        }
                    }
                    $html .= '</ul>';
                }
                $html .= '</td></tr>';
            }
                $html .= '</tbody></table>';
            }
            $html .= <<<EOT
			</td>
			</tr>
			</table>
EOT;
        }

        return $html;
    }
}
