<?php
namespace admin\modules\fund\models\parts;

use Yii;
use common\models\ObjectAbstract;
use yii\base\InvalidParamException;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use common\components\handler\VisitorHandler;
use common\ActiveRecord\NonTransactionDepositAndDrawAR;
use common\ActiveRecord\NonTransactionDepositAndDrawOperateLogAR;
use admin\models\parts\role\AdminAccount;
use common\models\parts\custom\CustomUser;
use business\models\parts\Account;
use admin\models\parts\trade\Wallet as AdminWallet;
use common\models\parts\trade\WalletAbstract;

class DepositAndDrawTicket extends ObjectAbstract{

    protected $AR;

    protected function _gettingList() : array{
        return [
            'operateType',
            'amount',
            'operateBrief',
            'operateDetail',
            'cancelReason',
            'status',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    const TARGET_USER_TYPE_CUSTOM = 1;
    const TARGET_USER_TYPE_BUSINESS = 2;

    const OPERATE_TYPE_DEPOSIT = 1;
    const OPERATE_TYPE_DRAW = 2;

    const STATUS_UNAUTHORIZED = 1;
    const STATUS_AUTHORIZED = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_FAILURE = 4;
    const STATUS_CANCEL = 5;

    const DRAW_MODE_STANDARD = 1;
    const DRAW_MODE_FORCE = 2;

    public $id;

    private $_cancelReason;
    private static $_authorize;
    private static $_authorizeConfig;
    private static $_authorizePassword;
    private $_targetUser;
    private static $_drawMode;

    public function init(){
        if(!$this->AR = NonTransactionDepositAndDrawAR::findOne($this->id)){
            throw new InvalidConfigException('unavailable id');
        }
    }

    public function getTargetUser(){
        if(is_null($this->_targetUser)){
            switch($this->AR->user_type){
                case self::TARGET_USER_TYPE_CUSTOM:
                    $this->_targetUser = new Customuser([
                        'id' => $this->AR->user_id,
                    ]);
                    break;

                case self::TARGET_USER_TYPE_BUSINESS:
                    $this->_targetUser = new Account([
                        'id' => $this->AR->user_id,
                    ]);
                    break;

                default:
                    throw new InvalidCallException('unavailable user type');
            }
        }
        return $this->_targetUser;
    }

    public function getTargetUserType(){
        return $this->AR->user_type;
    }

    public function getTargetUserId(){
        return $this->AR->user_id;
    }

    public function getTargetUserAccount(){
        return $this->AR->user_account;
    }

    public function getCreateTime(bool $unixTime = false){
        return $unixTime ? $this->AR->create_unixtime : ($this->AR->create_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->create_datetime);
    }

    public function getPassTime(bool $unixTime = false){
        return $unixTime ? $this->AR->pass_unixtime : ($this->AR->pass_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->pass_datetime);
    }

    public function getCancelTime(bool $unixTime = false){
        return $unixTime ? $this->AR->cancel_unixtime : ($this->AR->cancel_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->cancel_datetime);
    }

    public function getOperateTime(bool $unixTime = false){
        return $unixTime ? $this->AR->operate_unixtime : ($this->AR->operate_datetime == '0000-01-01 00:00:00' ? '' : $this->AR->operate_datetime);
    }

    public function getLogs($status = null){
        if(is_null($status)){
            $statuses = DepositAndDrawOperateLog::getStatuses();
        }else{
            $statuses = (array)$status;
        }
        $statuses = array_intersect($statuses, DepositAndDrawOperateLog::getStatuses());
        if(!$statuses){
            return [];
        }
        $logsData = Yii::$app->RQ->AR(new NonTransactionDepositAndDrawOperateLogAR)->all([
            'select' => ['id', 'operate_type'],
            'where' => [
                'non_transaction_deposit_and_draw_id' => $this->AR->id,
                'operate_type' => $statuses,
            ],
        ]);
        $logs = [];
        foreach($logsData as $logData){
            $logs[$logData['operate_type']] = new DepositAndDrawOperateLog([
                'id' => $logData['id'],
            ]);
        }
        return $logs;
    }

    public function fillCancelReason(string $reason, $return = 'throw'){
        if(!$reason)return Yii::$app->EC->callback($return, 'require non-empty reason');
        $this->_cancelReason = $reason;
        return $this;
    }

    public function execute($return = 'throw'){
        if(is_null(self::$_drawMode)){
            /**
             * return [
             *     'draw_mode' => 1|2
             * ];
             */
            $drawModeConfigFilePath = __DIR__ . '/DrawModeConfig.php';
            if(is_file($drawModeConfigFilePath)){
                try{
                    $drawMode = include($drawModeConfigFilePath);
                    if(is_array($drawMode) &&
                        array_key_exists('draw_mode', $drawMode) &&
                        in_array($drawMode['draw_mode'], [
                            self::DRAW_MODE_STANDARD,
                            self::DRAW_MODE_FORCE,
                        ])){
                        self::$_drawMode = $drawMode['draw_mode'];
                    }
                }catch(\Exception $e){
                    self::$_drawMode = self::DRAW_MODE_STANDARD;
                }
            }else{
                self::$_drawMode = self::DRAW_MODE_STANDARD;
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $ticketData = Yii::$app->db->createCommand("SELECT * FROM {{%non_transaction_deposit_and_draw}} WHERE [[id]] = :id FOR UPDATE")->
                bindValues([':id' => $this->AR->id])->
                queryOne();
            if($ticketData['status'] != self::STATUS_AUTHORIZED)throw new \Exception('incorrect status');
            $adminWallet = new AdminWallet([
                'id' => AdminWallet::WALLET_NON_TRANSACTION_DEPOSIT_AND_DRAW,
            ]);
            $userWallet = $this->targetUser->wallet;
            switch($this->operateType){
                case self::OPERATE_TYPE_DEPOSIT:
                    $userWallet->receiveType = WalletAbstract::RECEIVE_NON_TRANSACTION;
                    if(!$adminWallet->pay($this, $userWallet))throw new \Exception;
                    $this->status = self::STATUS_SUCCESS;
                    break;

                case self::OPERATE_TYPE_DRAW:
                    $adminWallet->receiveType = WalletAbstract::RECEIVE_NON_TRANSACTION;
                    if(self::$_drawMode == self::DRAW_MODE_STANDARD && $userWallet->rmb < $this->amount){
                        $this->status = self::STATUS_FAILURE;
                    }else{
                        if(!$userWallet->pay($this, $adminWallet))throw new \Exception;
                        $this->status = self::STATUS_SUCCESS;
                    }
                    break;

                default:
                    throw new \Exception('incorrect operate type');
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function fillOuterAuthorizePassword(string $password){
        self::$_authorizePassword = $password;
        return $this;
    }

    public function getOuterAuthorizePassword(){
        return self::$_authorizePassword ? : '';
    }

    public static function getAuthorizeConfig(){
        if(is_null(self::$_authorizeConfig)){
            /**
             * return [
             *     'require_authorize_password' => boolean,
             *     'password' => string,
             * ];
             */
            $authorizePasswordConfigFilePath = __DIR__ . '/AuthorizePasswordConfig.php';
            if(is_file($authorizePasswordConfigFilePath)){
                try{
                    $authorizePasswordConfig = include($authorizePasswordConfigFilePath);
                    if(is_array($authorizePasswordConfig) &&
                        array_key_exists('require_authorize_password', $authorizePasswordConfig) && 
                        array_key_exists('password', $authorizePasswordConfig) &&
                        is_string($authorizePasswordConfig['password']) &&
                        strlen($authorizePasswordConfig['password']) == 60){
                        $requireAuthorizePassword = (bool)$authorizePasswordConfig['require_authorize_password'];
                        $password = $authorizePasswordConfig['password'];
                    }else{
                        $requireAuthorizePassword = false;
                        $password = '';
                    }
                }catch(\Exception $e){
                    $requireAuthorizePassword = false;
                    $password = '';
                }
            }else{
                $requireAuthorizePassword = false;
                $password = '';
            }
            self::$_authorizeConfig = [
                'require_authorize_password' => $requireAuthorizePassword,
                'password' => $password,
            ];
        }
        return self::$_authorizeConfig;
    }

    public static function isRequireAuthorizePassword(){
        $config = static::getAuthorizeConfig();
        return (static::isRequireAuthorize() && $config['require_authorize_password']);
    }

    public static function getAuthorizePassword(){
        $config = static::getAuthorizeConfig();
        return $config['password'];
    }

    public function validateAuthorizePassword(){
        return Yii::$app->security->validatePassword($this->outerAuthorizePassword, static::getAuthorizePassword());
    }

    public function setStatus($status, AdminAccount $operateUser = null, $return = 'throw'){
        switch($status){
            case self::STATUS_AUTHORIZED:
                if(static::isRequireAuthorizePassword()){
                    if(!$this->validateAuthorizePassword()){
                        return Yii::$app->EC->callback($return, 'incorrect authorize password');
                    }
                }
                if($this->AR->status != self::STATUS_UNAUTHORIZED)return Yii::$app->EC->callback($return, 'incorrect status');
                if(is_null($operateUser))return Yii::$app->EC->callback($return, 'the operate user is missing');
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => $status,
                        'pass_datetime' => Yii::$app->time->fullDate,
                        'pass_unixtime' => Yii::$app->time->unixTime,
                    ]);
                    $this->recordOperation($status, $operateUser);
                    $transaction->commit();
                    return true;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, $e);
                }
                break;

            case self::STATUS_SUCCESS:
            case self::STATUS_FAILURE:
                if($this->AR->status != self::STATUS_AUTHORIZED)return Yii::$app->EC->callback($return, 'incorrect status');
                $result = Yii::$app->RQ->AR($this->AR)->update([
                    'status' => $status,
                    'operate_datetime' => Yii::$app->time->fullDate,
                    'operate_unixtime' => Yii::$app->time->unixTime,
                ], false);
                return $result ? true : Yii::$app->EC->callback($return, 'mysql');
                break;

            case self::STATUS_CANCEL:
                if(!in_array($this->AR->status, [
                    self::STATUS_UNAUTHORIZED,
                    self::STATUS_AUTHORIZED,
                ]))return Yii::$app->EC->callback($return, 'incorrect status');
                if(is_null($operateUser))return Yii::$app->EC->callback($return, 'the operate user is missing');
                if(!$this->_cancelReason)return Yii::$app->EC->callback($return, 'cancel reason is missing');
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => $status,
                        'cancel_datetime' => Yii::$app->time->fullDate,
                        'cancel_unixtime' => Yii::$app->time->unixTime,
                        'cancel_reason' => $this->_cancelReason,
                    ]);
                    $this->recordOperation($status, $operateUser);
                    $transaction->commit();
                    return true;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, $e);
                }
                break;

            default:
                return Yii::$app->EC->callback($return, 'unavailable status', '\yii\base\InvalidParamException');
                break;
        }
    }

    public static function isRequireAuthorize(){
        if(is_null(self::$_authorize)){
            /**
             * return [
             *     'authorize' => boolean,
             * ];
             */
            $authorizeConfigFilePath = __DIR__ . '/AuthorizeConfig.php';
            if(is_file($authorizeConfigFilePath)){
                try{
                    $authorize = include($authorizeConfigFilePath);
                    if(is_array($authorize) && array_key_exists('authorize', $authorize)){
                        $requestAuthorize = (bool)$authorize['authorize'];
                    }
                }catch(\Exception $e){
                    $requestAuthorize = true;
                }
            }else{
                $requestAuthorize = true;
            }
            self::$_authorize = $requestAuthorize;
        }
        return self::$_authorize;
    }

    public static function generate(array $params, $return = 'throw'){
        $operateUser = null;
        $targetUser = null;
        $operateType = null;
        $amount = null;
        $operateBrief = null;
        $operateDetail = null;
        extract($params, EXTR_IF_EXISTS);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $generator = new DepositAndDrawTicketGenerator([
                'operateUser' => $operateUser,
                'targetUser' => $targetUser,
                'operateType' => $operateType,
                'amount' => $amount,
                'operateBrief' => $operateBrief,
                'operateDetail' => $operateDetail,
            ]);
            $ticket = $generator->generate();
            $ticket->recordOperation(self::STATUS_UNAUTHORIZED, $operateUser);
            if(!static::isRequireAuthorize()){
                $ticket->setStatus(self::STATUS_AUTHORIZED, $operateUser);
            }
            $transaction->commit();
            return $ticket;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    protected function recordOperation($status, AdminAccount $account, $return = 'throw'){
        if(!in_array($status, static::getStatuses()))throw new InvalidParamException('unavailable status');
        if(Yii::$app->user->isGuest)throw new InvalidCallException('the operate user must be logged in');
        switch($status){
            case self::STATUS_UNAUTHORIZED:
                $operateType = DepositAndDrawOperateLog::OPERATE_TYPE_CREATE;
                break;

            case self::STATUS_AUTHORIZED:
                $operateType = DepositAndDrawOperateLog::OPERATE_TYPE_PASS;
                break;

            case self::STATUS_CANCEL:
                $operateType = DepositAndDrawOperateLog::OPERATE_TYPE_CANCEL;
                break;

            default:
                throw new InvalidCallException('unable to record this status');
        }
        try{
            return Yii::$app->RQ->AR(new NonTransactionDepositAndDrawOperateLogAR)->insert([
                'non_transaction_deposit_and_draw_id' => $this->id,
                'operate_type' => $operateType,
                'user_type' => DepositAndDrawOperateLog::OPERATE_USER_TYPE_ADMIN,
                'user_id' => $account->id,
                'user_account' => $account->account,
                'operate_datetime' => Yii::$app->time->fullDate,
                'operate_unixtime' => Yii::$app->time->unixTime,
                'user_ip' => VisitorHandler::collectUserIp(),
                'user_request_header' => serialize(VisitorHandler::collectRequestHeader()),
            ], $return) ? true : false;
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public static function getStatuses(){
        return [
            self::STATUS_UNAUTHORIZED,
            self::STATUS_AUTHORIZED,
            self::STATUS_SUCCESS,
            self::STATUS_FAILURE,
            self::STATUS_CANCEL,
        ];
    }
}
