<?php
namespace admin\models\parts\trade;

use Yii;
use common\models\parts\trade\RechargeMethodAbstract;

class RechargeMethod extends RechargeMethodAbstract{

    public $method;

    private $_method;

    public function init(){
        if(self::canRecharge($this->method)){
            $this->_method = $this->method;
        }
    }

    public function getCurrentRechargeMethod(){
        return $this->_method;
    }

    public static function getRechargeMethods(){
        return [
            self::METHOD_WX_INWECHAT,
        ];
    }
}
