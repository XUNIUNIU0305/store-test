<?php
namespace common\models\parts\trade\recharge\wechat;

use Yii;
use common\models\parts\basic\OpensslAbstract;

class UrlParamCrypt extends OpensslAbstract{

    public $method = 'AES256';

    public $options = 0;

    protected function getPassword(){
        return __FILE__;
    }

    /**
     * 获取随机熵
     * 保存在SESSION中，每次登陆加密熵不同
     *
     * @return binary
     */
    protected function getIV(){
        $sessionName = '__' . __CLASS__;
        if(Yii::$app->session->has($sessionName)){
            return Yii::$app->session->get($sessionName);
        }else{
            $iv = $this->generateIV();
            Yii::$app->session->set($sessionName, $iv);
            return $iv;
        }
    }

    public function encrypt($data){
        return urlencode(parent::encrypt(serialize($data)));
    }

    public function decrypt($data){
        return unserialize(parent::decrypt($data));
    }
}
