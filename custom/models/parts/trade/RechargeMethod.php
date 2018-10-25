<?php
namespace custom\models\parts\trade;

use Yii;
use common\models\parts\trade\RechargeMethodAbstract;

class RechargeMethod extends RechargeMethodAbstract{

    public $method;

    protected $_method;

    public function init(){
        if(self::canRecharge($this->method)){
            $this->_method = $this->method;
        }
    }

    /**
     * 获取实例化对象设置的当前充值方式
     *
     * @return int|null
     */
    public function getCurrentRechargeMethod(){
        return $this->_method;
    }

    /**
     * 获取充值方式列表
     *
     * @return array
     */
    public static function getRechargeMethods(){
        return [
            self::METHOD_ALIPAY,
            self::METHOD_WX_INWECHAT,
            self::METHOD_GATEWAY_PERSON,
            self::METHOD_GATEWAY_CORP,
            self::METHOD_ABCHINA_GATEWAY,
        ];
    }
}
