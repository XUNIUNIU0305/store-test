<?php
namespace common\models\parts\trade;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\PaymentMethodAR;

class PaymentMethodList extends Object{

    public $method;

    private $_method;

    private static $_methodNames = [];

    public function init(){
        if(is_null($this->method)){
            $this->_method = $this->method;
        }else{
            if(is_numeric($this->method)){
                $this->method = (array)$this->method;
            }
            if(is_array($this->method)){
                $paymentQuantity = Yii::$app->RQ->AR(new PaymentMethodAR)->count([
                    'where' => [
                        'id' => $this->method,
                    ],
                ]);
                if($paymentQuantity == count($this->method)){
                    $this->_method = $this->method;
                }else{
                    throw new InvalidConfigException;
                }
            }else{
                throw new InvalidConfigException;
            }
        }
    }

    public function getPaymentMethod(){
        $paymentMethod = Yii::$app->RQ->AR(new PaymentMethodAR)->all([
            'select' => ['id', 'name', 'img_url'],
            'filterWhere' => ['id' => $this->_method],
        ]);
        return empty($paymentMethod) ? [] : array_column($paymentMethod, null, 'id');
    }

    public static function queryMethodName($id){
        if(!$id)return '';
        if(!isset(static::$_methodNames[$id])){
            static::$_methodNames[$id] = PaymentMethodAR::find()
                ->select(['name'])
                ->where(['id' => $id])
                ->scalar() ?? '未知支付方式';
        }
        return static::$_methodNames[$id];
    }
}
