<?php
namespace admin\modules\fund\models\parts;

use Yii;
use yii\base\InvalidConfigException;
use common\models\ObjectAbstract;
use common\components\handler\Handler;
use admin\models\parts\role\AdminAccount;
use common\ActiveRecord\NonTransactionDepositAndDrawOperateLogAR;

class DepositAndDrawOperateLog extends ObjectAbstract{

    protected $AR;

    protected function _gettingList() : array{
        return [
            'operate_type',
            'user_type',
            'user_id',
            'user_account',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    const OPERATE_TYPE_CREATE = 0;
    const OPERATE_TYPE_PASS = 2;
    const OPERATE_TYPE_CANCEL = 5;

    const OPERATE_USER_TYPE_ADMIN = 1;

    public $id;

    private $_ticket;
    private $_operateUser;

    public function init(){
        if(!$this->AR = NonTransactionDepositAndDrawOperateLogAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
    }

    public function getTicket(){
        if(is_null($this->_ticket)){
            $this->_ticket = new DepositAndDrawTicket([
                'id' => $this->AR->non_transaction_deposit_and_draw_id,
            ]);
        }
        return $this->_ticket;
    }

    public function getUser(){
        if(is_null($this->_operateUser)){
            $this->_operateUser = new AdminAccount([
                'id' => $this->AR->user_id,
            ]);
        }
        return $this->_operateUser;
    }

    public function getUserIp(bool $long = false){
        return $long ? $this->AR->user_ip : Handler::ipLongToStr($this->AR->user_ip);
    }

    public function getUserRequestHeader(){
        return unserialize($this->AR->user_request_header);
    }

    public static function getStatuses(){
        return [
            self::OPERATE_TYPE_CREATE,
            self::OPERATE_TYPE_PASS,
            self::OPERATE_TYPE_CANCEL,
        ];
    }
}
