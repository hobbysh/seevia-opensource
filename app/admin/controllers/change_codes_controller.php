<?php

class ChangeCodesController extends AppController
{
    public $name = 'ChangeCodes';
    public $uses = array('Brand','Warehouse','Shelf','Product','ProductI18n','Stock','TransferProduct','Inbound','InboundProduct','OutboundProduct','ShelfProduct','Order','OrderProduct','TaobaoTrade','TaobaoOrder','ChangeCode','TaobaoItem','TaobaoShop');

    public function index()
    {
        $data = $this->ChangeCode->find('all', array('conditions' => array('status' => 0, 'old_code !=' => '', 'old_code !=' => null), 'limit' => 100));
    //	$old_code_list = $this->TaobaoItem->find('list',array('fields'=>array('TaobaoItem.num_iid','TaobaoItem.outer_id'),'conditions'=>array('TaobaoItem.outer_id !='=>'')));
        $product_code_list = $this->Product->find('list', array('fields' => array('Product.code')));
        $brand_code_list = $this->Brand->find('list', array('fields' => array('Brand.code', 'Brand.id')));
        $y = $n = 0;
        foreach ($data as $k => $v) {
            //			if(isset($old_code_list[$v['ChangeCode']['num_iid']])&&!empty($old_code_list[$v['ChangeCode']['num_iid']])){
//				$v['ChangeCode']['old_code']=$old_code_list[$v['ChangeCode']['num_iid']];
//				$this->ChangeCode->save($v);
//				
                if (in_array($v['ChangeCode']['new_code'], $product_code_list)) {
                    ++$n;
                    echo 'num_iid:'.$v['ChangeCode']['num_iid'].' outer_id:'.$v['ChangeCode']['new_code'].'新货号已存在<br />';
                    continue;
                }
            if (in_array(substr($v['ChangeCode']['new_code'], 0, 4), $brand_code_list)) {
                $brand_id = $brand_code_list[substr($v['ChangeCode']['new_code'], 0, 4)];
            } else {
                $brand_id = 0;
            }
            echo $brand_id.'<br />';

/*				$this->Product->updateAll(array('Product.code'=>"'".$v['ChangeCode']['new_code']."'",'Product.brand_id'=>$brand_id),array('Product.code'=>$v['ChangeCode']['old_code']));
                $this->TaobaoItem->updateAll(array('TaobaoItem.outer_id'=>"'".$v['ChangeCode']['new_code']."'"),array('TaobaoItem.outer_id'=>$v['ChangeCode']['old_code']));
                $this->Stock->updateAll(array('Stock.product_code'=>"'".$v['ChangeCode']['new_code']."'"),array('Stock.product_code'=>$v['ChangeCode']['old_code']));
                $this->InboundProduct->updateAll(array('InboundProduct.product_code'=>"'".$v['ChangeCode']['new_code']."'"),array('InboundProduct.product_code'=>$v['ChangeCode']['old_code']));
                $this->OutboundProduct->updateAll(array('OutboundProduct.product_code'=>"'".$v['ChangeCode']['new_code']."'"),array('OutboundProduct.product_code'=>$v['ChangeCode']['old_code']));
                $this->TransferProduct->updateAll(array('TransferProduct.product_code'=>"'".$v['ChangeCode']['new_code']."'"),array('TransferProduct.product_code'=>$v['ChangeCode']['old_code']));
                $this->OrderProduct->updateAll(array('OrderProduct.product_code'=>"'".$v['ChangeCode']['new_code']."'"),array('OrderProduct.product_code'=>$v['ChangeCode']['old_code']));
                $v['ChangeCode']['status']=1;
                $this->ChangeCode->save($v);
                $y++;
        */
            //}
//			else{
//				$n++;
//				echo "num_iid:".$v['ChangeCode']['num_iid']." outer_id:".$v['ChangeCode']['new_code']."老货号为空<br />";
//			}
//			
        }
        echo '成功:'.$y.'  失败:'.$n;

        die;
    }
}
