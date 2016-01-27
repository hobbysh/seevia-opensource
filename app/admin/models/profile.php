<?php
class profile extends AppModel
{
    /*
    * @var $useDbConfig ���ݿ�����
    */
    public $useDbConfig = 'default';
    /*
     * @var $name Profile 
     */
    public $name = 'Profile';
    public $hasOne = array(
                    'ProfileI18n' => array('className' => 'ProfileI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'profile_id',
                        ),
                  );
    public function set_locale($locale)
    {
        $this->hasOne['ProfileI18n']['conditions'] = " ProfileI18n.locale = '".$locale."'";
    }

    /**
     * localeformat����������ṹ����.
     *
     * @param int $id ���������
     *
     * @return array $lists_formated ����Profile�������Ե���Ϣ
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Profile.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Profile'] = $v['Profile'];
            $lists_formated['ProfileI18n'][] = $v['ProfileI18n'];
            foreach ($lists_formated['ProfileI18n']as $key => $val) {
                $lists_formated['ProfileI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

//	function getProfile($csv_type,$type=''){
//		$new_key_arr=array();
////		$new_key_arr['SVCART']=array();
////		$new_key_arr['desc']=array();
//		$profile_info=$this->find("all",array('conditions'=>array("Profile.code"=>$csv_type,"Profile.status"=>1)));
//		foreach($profile_info as $k => $v){
//			$fields_k=explode(".",$v['ProfileFiled']['code']);
//			$new_key_arr[]=isset($fields_k[1])?$fields_k[1]:'';
////			$new_key_arr['SVCART'][]=isset($fields_k[1])?$fields_k[1]:'';
////			$new_key_arr['desc'][]=$v['ProfileFiled']['description'];
//		}
//		return $new_key_arr;
//	}
}
