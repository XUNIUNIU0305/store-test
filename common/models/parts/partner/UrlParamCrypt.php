<?php
namespace common\models\parts\partner;

use Yii;
use common\models\parts\basic\OpensslAbstract;

class UrlParamCrypt extends OpensslAbstract{

    public $method = 'AES256';
    public $options = 0;

    protected function getPassword(){
        return __FILE__;
    }

    protected function getIV(){
        return 'partner_apply_12';
    }

    public function encrypt($data){
        return urlencode(parent::encrypt(serialize($data)));
    }

    public function decrypt($data){
        return unserialize(parent::decrypt($data));
    }
}
