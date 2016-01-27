<?php

class AdvertisementEffectDefault extends AppModel
{
    public $useDbConfig = 'cms';
    public $useTable = 'advertisement_effects_defaults';

    public $name = 'AdvertisementEffectDefault';

    public function getformatcode($locale)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $advertisement_effect_default = $this->find('all', array('cache' => $node, 'conditions' => array('locale' => $locale)));

        return $advertisement_effect_default;
    }

    public function getformatconflgs($code)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $advertisement_effect_conflgs = $this->find('first', array('cache' => $node, 'fields' => array('configs'), 'conditions' => array('type' => $code)));
        $conflg = json_decode($advertisement_effect_conflgs['AdvertisementEffectDefault']['configs']);
        foreach ($conflg as $k => $v) {
            $conflgs[$k] = (array) $conflg[$k];
        }

        return $conflgs;
    }
}
