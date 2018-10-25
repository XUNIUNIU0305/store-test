<?php
namespace common\models\parts\trade\recharge\nanjing\draw;

use Yii;
use common\models\ObjectAbstract;
use common\ActiveRecord\UserDrawAR;
use yii\base\InvalidConfigException;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;
use common\models\parts\trade\recharge\nanjing\account\NanjingAccount;

class DrawTicket extends ObjectAbstract{

    const STATUS_APPLY = 0;
    const STATUS_PASS = 1;
    const STATUS_REJECT = 2;
    const STATUS_FAILURE = 3;
    const STATUS_SUCCESS = 4;

    public $id;
    public $drawNumber;

    protected $AR;

    private $_userAccount;
    private $_nanjingAccount;

    public function init(){
        if($this->id){
            $this->AR = UserDrawAR::findOne($this->id);
        }elseif($this->drawNumber){
            $this->AR = UserDrawAR::findOne(['draw_number' => $this->drawNumber]);
        }else{
            throw new InvalidConfigException('unavailable neithor id nor drawNumber');
        }
        if(!$this->AR)throw new InvalidConfigException('unavailable AR');
        $this->id = $this->AR->id;
        $this->drawNumber = $this->AR->draw_number;
    }

    protected function _gettingList() : array{
        return [
            'drawNumber',
            'rmb',
            'userId',
            'userType',
            'nanjingAccountId',
            'nanjingPayBalanceId',
            'nanjingDrawId',
            'nanjingRefundId',
            'verifyMsg',
            'handleErrMsg',
            'status',
        ];
    }

    protected function _settingList() : array{
        return [
            'verifyMsg',
            'handleErrMsg',
            'nanjingPayBalanceId',
            'nanjingDrawId',
            'nanjingRefundId',
        ];
    }

    public function getIsLock(){
        return $this->AR->operation_lock ? true : false;
    }

    public function setLock(bool $lock, $return = 'throw'){
        $lock = $lock ? 1 : 0;
        if($this->AR->operation_lock == $lock)return Yii::$app->EC->callback($return, 'locked already');
        return (Yii::$app->RQ->AR($this->AR)->update([
            'operation_lock' => $lock,
        ], $return) == 1 ? true : Yii::$app->EC->callback($return, 'unchange lock status'));
    }

    public function setStatus($status, $return = 'throw'){
        $throwMsg = 'unable to set status: ' . $status;
        try{
            switch($status){
                case self::STATUS_PASS:
                    if($this->status != self::STATUS_APPLY)return Yii::$app->EC->callback($return, $throwMsg);
                    if($this->isLock)return Yii::$app->EC->callback($return, 'this draw ticket is locked');
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => $status,
                        'pass_datetime' => date('Y-m-d H:i:s', $time = time()),
                        'pass_unixtime' => $time,
                    ]);
                    return true;
                    break;

                case self::STATUS_REJECT:
                    if($this->status != self::STATUS_APPLY)return Yii::$app->EC->callback($return, $throwMsg);
                    if($this->isLock)return Yii::$app->EC->callback($return, 'this draw ticket is locked');
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => $status,
                        'reject_datetime' => date('Y-m-d H:i:s', $time = time()),
                        'reject_unixtime' => $time,
                    ]);
                    return true;
                    break;

                case self::STATUS_FAILURE:
                    if($this->status != self::STATUS_PASS)return Yii::$app->EC->callback($return, $throwMsg);
                    if($this->isLock)return Yii::$app->EC->callback($return, 'this draw ticket is locked');
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => $status,
                        'failure_datetime' => date('Y-m-d H:i:s', $time = time()),
                        'failure_unixtime' => $time,
                    ]);
                    return true;
                    break;

                case self::STATUS_SUCCESS:
                    if($this->status != self::STATUS_PASS)return Yii::$app->EC->callback($return, $throwMsg);
                    if($this->isLock)return Yii::$app->EC->callback($return, 'this draw ticket is locked');
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => $status,
                        'success_datetime' => date('Y-m-d H:i:s', $time = time()),
                        'success_unixtime' => $time,
                    ]);
                    return false;
                    break;

                default:
                    return Yii::$app->EC->callback($return, $throwMsg);
            }
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function getUserAccount(){
        if(is_null($this->_userAccount)){
            $this->_userAccount = false;
            switch($this->userType){
                case AccountAbstract::ACCOUNT_TYPE_CUSTOM:
                    break;

                case AccountAbstract::ACCOUNT_TYPE_SUPPLY:
                    break;

                case AccountAbstract::ACCOUNT_TYPE_BUSINESS:
                    $this->_userAccount = new \business\models\parts\trade\nanjing\BusinessAccount(['id' => $this->userId]);
                    break;

                case AccountAbstract::ACCOUNT_TYPE_ADMIN:
                    break;

                default:
                    break;
            }
        }
        return $this->_userAccount;
    }

    public function getNanjingAccount(){
        if(is_null($this->_nanjingAccount)){
            $this->_nanjingAccount = new NanjingAccount([
                'id' => $this->nanjingAccountId,
            ]);
        }
        return $this->_nanjingAccount;
    }

    public function getApplyTime(bool $unixTime = false){
        return $unixTime ? $this->AR->apply_unixtime : $this->AR->apply_datetime;
    }

    public function getPassTime(bool $unixTime = false){
        return $unixTime ? $this->AR->pass_unixtime : $this->AR->pass_datetime;
    }

    public function getRejectTime(bool $unixTime = false){
        return $unixTime ? $this->AR->reject_unixtime : $this->AR->reject_datetime;
    }

    public function getFailureTime(bool $unixTime = false){
        return $unixTime ? $this->AR->failure_unixtime : $this->AR->failure_datetime;
    }

    public function getSuccessTime(bool $unixTime = false){
        return $unixTime ? $this->AR->success_unixtime : $this->AR->success_datetime;
    }

    public static function getStatuses(){
        return [
            self::STATUS_APPLY,
            self::STATUS_PASS,
            self::STATUS_REJECT,
            self::STATUS_FAILURE,
            self::STATUS_SUCCESS,
        ];
    }
}
