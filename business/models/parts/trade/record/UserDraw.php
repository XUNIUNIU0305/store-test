<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-7-25
 * Time: 下午2:42
 */
namespace business\models\parts\trade\record;

use common\ActiveRecord\UserDrawAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class UserDraw extends Object {

    public $log_id;

    protected $AR;

    public function init(){
        if(!$this->log_id ||
            !$this->AR = UserDrawAR::findOne(['id'=>$this->log_id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getOrderNo(){
        return $this->AR->draw_number;
    }

}