<?php
namespace common\models\parts\wechat;

use Yii;
use common\models\parts\basic\OpensslAbstract;

class UrlParamCrypt extends OpensslAbstract{

    public $method = 'AES128';

    public $options = 0;

    protected function getPassword(){
        return __FILE__;
    }

    protected function getIV(){
        return 'WechaT1234567890';
    }

    public function encrypt($data){
        return parent::encrypt(serialize($data));
    }

    public function decrypt($data){
        return unserialize(parent::decrypt($data));
    }
}
