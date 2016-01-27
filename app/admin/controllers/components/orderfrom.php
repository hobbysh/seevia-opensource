<?php

class OrderfromComponent extends Object
{
    public $name = 'Orderfrom'; // the name of your component

    public function get($controller, $child = 0)
    {

//    	if($this->admin['type']=="S"){
    //		//订单来源
            $order_type = array();
        $order_type_arr = array('website' => $controller->ld['order_site']);
        if (!in_array('APP-PRODUCT-VOLUME', $controller->apps)) {
            $order_type['website'] = array('front' => $controller->ld['frontend'],'backend' => $controller->ld['backend']);
        } else {
            $order_type['website'] = array('网站' => $controller->ld['website'],'批发' => $controller->ld['order_wholesale']);
        }

            //pr($controller->apps['Applications']);die;
            //if(in_array('APP-SHOP',$controller->apps['Applications'])){echo 1;}else{ echo 2;};die;
//			if(array_key_exists('APP-SHOP',$controller->apps['Applications'])){
//				//in_array('APP-SHOP',)
//				$order_type_arr['taobao']='淘宝';
//				$controller->loadModel('TaobaoShop');
//				$order_type_arr2 = $controller->TaobaoShop->find('all',array('conditions'=>array("status"=>1),'order'=>'orderby'));
//				//pr($order_type_arr2);die;
//				if(!empty($order_type_arr2)){
//					foreach ($order_type_arr2 as $k => $v){
//						$order_type['taobao'][$v['TaobaoShop']['nick']]=$v['TaobaoShop']['nick'];
//					}
//				}
//			}
//			if(array_key_exists('APP-SHOP',$controller->apps['Applications'])){
//				//in_array('APP-SHOP',$controller->apps)
//				//$order_type_arr['jingdong']='京东';
//				//$order_type['jingdong']=array('艾婷家居京东店'=>'艾婷家居京东店');
//				$order_type_arr['jingdong']='京东';
//				$controller->loadModel('JingdongShop');
//				$order_type_arr2 = $controller->JingdongShop->find('all',array('conditions'=>array("status"=>1),'order'=>'orderby'));
//				if(!empty($order_type_arr2)){
//					foreach ($order_type_arr2 as $k => $v){
//						$order_type['jingdong'][$v['JingdongShop']['vender_id']]=$v['JingdongShop']['nick'];
//					}
//				}
//			}
//			if(array_key_exists('APP-SHOP',$controller->apps['Applications'])){
//				//in_array('APP-SHOP',$controller->apps)
//				//$order_type_arr['jingdong']='京东';
//				//$order_type['jingdong']=array('艾婷家居京东店'=>'艾婷家居京东店');
//				$order_type_arr['paipai']='拍拍';
//				$controller->loadModel('PaipaiShop');
//				$order_type_arr2 = $controller->PaipaiShop->find('all',array('conditions'=>""));
//				//pr($order_type_arr2);die;
//				if(!empty($order_type_arr2)){
//					foreach ($order_type_arr2 as $k => $v){
//						$order_type['paipai'][$v['PaipaiShop']['shopName']]=$v['PaipaiShop']['shopName'];					//$v['PaipaiShop']['sellerUin']替换成shopName，测试数据sellerUin为0
//					}
//					//pr($order_type);
//				}
//			}
            //TODO 判断门店应用
//			if(array_key_exists('APP-SHOP',$controller->apps['Applications'])){
//				$order_type_arr['store']=$controller->ld['order_store'];//门店
//				$controller->loadModel('Store');
//				$controller->Store->set_locale($controller->backend_locale);
//				$stores = $controller->Store->find('all',array('conditions'=>array("status"=>1),'fields'=>array('store_sn','StoreI18n.name'),'order'=>'orderby'));
//				if(!empty($stores)){
//					foreach ($stores as $kk => $vv) {
//						$order_type['store'][$vv['Store']['store_sn']]=$vv['StoreI18n']['name'];
//						$order_type_arr[$vv['Store']['store_sn']] = $vv['StoreI18n']['name'];
//					}
//					
//				}
//			}
            //分销
//			if(in_array('APP-SHOP',$controller->apps)){
//				$order_type_arr['fenxiao']='分销';
//			//	pr ($this->loadModel('TaobaoShop'));die();
//				$controller->loadModel('TaobaoShop');
//				$order_type_arr2 = $controller->TaobaoShop->find('all',array('conditions'=>array("status"=>1,"is_fenxiao"=>1),'order'=>'orderby'));
//			
//				if(!empty($order_type_arr2)){
//					foreach ($order_type_arr2 as $k => $v){
//						$order_type['fenxiao'][$v['TaobaoShop']['nick']]=$v['TaobaoShop']['nick'];
//					}				
//				}
//			}
//		}
        //经销商
        /*if(in_array('APP-DEALER',$controller->apps)){
            $controller->loadModel('Dealer');
            $order_type_arr['dealer']='经销商';
            if($this->admin['type']=="S"){
            //	$dealers = $controller->Dealer->find('all',array('conditions'=>array("status"=>1),'fields'=>array('id','name'),'order'=>'orderby'));
                $dealers = $controller->Dealer->all_tree();
            //	pr($dealers);die;
            }elseif($this->admin['type']=="D"){
                $dealers = $controller->Dealer->find('all',array('conditions'=>array("status"=>1,"id"=>$this->admin['type_id']),'fields'=>array('id','name'),'order'=>'orderby'));
                $dealers_tree=$controller->Dealer->all_tree($this->admin['type_id']);
                if(!empty($dealers_tree)&&$child==0){
                    $dealers[0]['SubDealer']=$dealers_tree;
                }
            }
            if(!empty($dealers)){
                foreach ($dealers as $k => $v) {
                    $order_type['dealer'][$v['Dealer']['id']]=$v['Dealer']['name'];
                    $order_type_arr[$v['Dealer']['id']] = $v['Dealer']['name'];
                    if(!empty($v['SubDealer'])){
                        foreach ($v['SubDealer'] as $kk => $vv){
                            if(!empty($vv['Dealer']['id'])){
                                $order_type['dealer'][$vv['Dealer']['id']]='--'.$vv['Dealer']['name'];
                                $order_type_arr[$vv['Dealer']['id']] = '--'.$vv['Dealer']['name'];
                                if(!empty($vv['SubDealer'])){
                                    foreach ($vv['SubDealer'] as $kkk => $vvv){
                                        if(!empty($vvv['Dealer']['id'])){
                                            $order_type['dealer'][$vvv['Dealer']['id']]='----'.$vvv['Dealer']['name'];
                                            $order_type_arr[$vvv['Dealer']['id']] = '----'.$vvv['Dealer']['name'];
                                            if(!empty($vvv['SubDealer'])){
                                                foreach ($vvv['SubDealer'] as $kkkk => $vvvv){
                                                    $order_type['dealer'][$vvvv['Dealer']['id']]='------'.$vvvv['Dealer']['name'];
                                                    $order_type_arr[$vvvv['Dealer']['id']] = '------'.$vvvv['Dealer']['name'];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }*/
        //pr($order_type);
        $controller->set('order_type_arr', $order_type_arr);
        $controller->set('order_type', $order_type);
//		pr($order_type);
        return array('order_type_arr' => $order_type_arr,'order_type' => $order_type);
        //
    }
}
