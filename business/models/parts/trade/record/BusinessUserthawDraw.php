<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-7-25
 * Time: 下午2:37
 */
namespace business\models\parts\trade\record;

use common\ActiveRecord\BusinessUserThawDrawAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class BusinessUserthawDraw extends Object {

    public $log_id;

    protected $AR;

    public function init(){
        if(!$this->log_id ||
            !$this->AR = BusinessUserthawDrawAR::findOne(['business_user_thaw_log_id'=>$this->log_id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getUserDrawId(){
        return $this->AR->user_draw_id;
    }

}