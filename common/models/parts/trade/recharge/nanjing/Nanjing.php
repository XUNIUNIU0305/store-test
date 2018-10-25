<?php
namespace common\models\parts\trade\recharge\nanjing;

use Yii;
use yii\base\Object;
use common\components\amqp\Message;
use common\ActiveRecord\NanjingAccountAR;
use common\models\parts\trade\recharge\nanjing\data\Base;
use common\models\parts\trade\recharge\nanjing\bank\Bank;
use common\models\parts\trade\recharge\nanjing\account\NanjingAccount;
use common\models\parts\trade\recharge\nanjing\data\NanjingAccount as NJAccount;
use common\models\parts\trade\recharge\nanjing\data\NanjingActivation;
use common\models\parts\trade\recharge\nanjing\data\NanjingQueryCustomerInfo;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use common\models\parts\trade\recharge\nanjing\account\MainAccount;
use common\models\parts\trade\recharge\nanjing\handler\SeqNoGenerator;
use common\models\parts\trade\recharge\nanjing\handler\DepositSeqNoGenerator;
use common\models\parts\trade\recharge\nanjing\handler\DirectDrawSeqNoGenerator;
use common\models\parts\trade\recharge\nanjing\handler\TransSeqNoGenerator;
use common\models\parts\trade\recharge\nanjing\handler\RefundSeqNoGenerator;
use common\models\parts\trade\recharge\nanjing\data\NanjingPayment;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;
use common\models\parts\trade\recharge\nanjing\data\NanjingCaptcha;
use common\models\parts\trade\recharge\nanjing\data\NanjingDeposit;
use common\models\parts\trade\recharge\nanjing\data\NanjingDraw;
use common\models\parts\trade\recharge\nanjing\data\NanjingQueryBalance;
use common\models\parts\trade\recharge\nanjing\data\NanjingQueryDepositAndDefrayalStatement;
use common\models\parts\trade\recharge\nanjing\data\NanjingGatewayPayment as NJGatewayPayment;
use common\models\parts\trade\recharge\nanjing\data\NanjingRefund;
use common\models\parts\trade\recharge\nanjing\data\NanjingQueryAccountStatement;
use admin\models\parts\sms\Sms;
use common\models\parts\sms\SmsSender;

class Nanjing extends Object{

    const ENV_TEST = 1;
    const ENV_PROD = 2;

    const OPERATION_PAY_OF_DRAW = 1; //user_draw
    const OPERATION_DRAW_OF_DRAW = 2; //user_draw
    const OPERATION_REFUND_OF_DRAW = 3; //user_draw
    const OPERATION_DEPOSIT = 4;
    const OPERATION_DIRECT_DRAW = 5;
    const OPERATION_GATEWAY_APPLY = 6;

    public $environment;

    protected $merchantId;
    protected $paygateCert;
    protected $merchantCert;
    protected $merchantCertPassword;
    protected $gateway;

    private $_mainAccount;

    public function init(){
        if(is_null($this->environment)){
            $this->environment = YII_ENV == 'prod' ? self::ENV_PROD : self::ENV_TEST;
        }
        $certPath = __DIR__ . '/data/cert/';
        if($this->environment == self::ENV_PROD){
            $this->merchantId = Yii::$app->params['NANJING_Merchant_Id'];
            $this->paygateCert = $certPath . 'cert.cer';
            $this->merchantCert = $certPath . 'merchant.pfx';
            $this->merchantCertPassword = Yii::$app->params['NANJING_Merchant_Password'];
            $this->gateway = Yii::$app->params['NANJING_Gateway'];
        }else{
            $this->merchantId = 6666;
            $this->paygateCert = $certPath . 'cert_for_test.cer';
            $this->merchantCert = $certPath . 'merchant_for_test.pfx';
            $this->merchantCertPassword = '111111';
            $this->gateway = 'http://222.190.125.58/blocktrade/main';
        }
        Yii::$app->db->queryMaster = true;
    }

    public function getMainAccount(){
        if(is_null($this->_mainAccount)){
            $this->_mainAccount = new MainAccount([
                'env' => $this->environment,
            ]);
        }
        return $this->_mainAccount;
    }

    protected function getCommonParams(){
        return [
            'MerchantId' => $this->merchantId,
            'merchantCert' => $this->merchantCert,
            'merchantCertPassword' => $this->merchantCertPassword,
            'gateway' => $this->gateway,
            'paygateCert' => $this->paygateCert,
        ];
    }

    /**
     * 创建账户
     *
     * @param array $params 详见\common\models\parts\trade\recharge\nanjing\data\NanjingAccount参数配置
     * @param AccountAbstract $account 本站账户
     * @param mix 错误回调
     *
     * @return Object NanjingAccount
     */
    public function createAccount(array $params, AccountAbstract $account, $return = 'throw'){
        if(NanjingAccountAR::findOne(['nanjing_userid' => $account->getMerUserid(), 'is_available' => NanjingAccount::STATUS_AVAILABLE]))return Yii::$app->EC->callback($return, 'this account has been created');
        $params = array_merge($params, $this->getCommonParams(), [
            'MerUserId' => $account->getMerUserId(),
            'OperType' => 1,
            'IsRate' => 0,
        ]);
        $nanjingAccount = new NJAccount($params);
        return $this->executeAction($nanjingAccount, function($callback)use($params, $account){
            $createAccount = new \common\models\parts\trade\recharge\nanjing\message\CreateAccount([
                'callback' => $callback,
                'accountObj' => $account::className(),
                'accountId' => $account->id,
                'mobilePhone' => $params['MobilePhone'],
                'cifType' => $params['CifType'],
                'cifName' => $params['CifName'],
                'idType' => $params['IdType'],
                'idNo' => $params['IdNo'],
                'acctType' => $params['AcctType'],
                'acctName' => $params['AcctName'],
                'acctNo' => $params['AcctNo'],
                'bankType' => Bank::getBankType($params['BranchId']) ? : 0,
                'branchId' => $params['BranchId'],
                'createDatetime' => Yii::$app->time->fullDate,
                'createUnixtime' => Yii::$app->time->unixTime,
            ]);
            $this->sendMessage($createAccount);
            return true;
        }, $return);
    }

    public function activateApply(NanjingAccount $account, $return = 'throw'){
        if($account->isActive)return Yii::$app->EC->callback($return, 'this account had been activated');
        $params = array_merge($this->getCommonParams(), [
            'MerUserId' => $account->nanjingUserid,
        ]);
        $nanjingActivation = new NanjingActivation($params);
        return $this->executeAction($nanjingActivation, function($callback)use($account){
            $activateApply = new \common\models\parts\trade\recharge\nanjing\message\ActivateApply([
                'callback' => $callback,
                'accountObj' => $account::className(),
                'accountId' => $account->id,
                'verSeqNoUnixtime' => Yii::$app->time->unixTime,
            ]);
            $this->sendMessage($activateApply);
            return $callback->VerSeqNo;
        }, $return);
    }

    public function activateAccount(NanjingAccount $account, $checkAmount, string $verSeqNo = null, $return = 'throw'){
        if($account->isActive)return Yii::$app->EC->callback($return, 'this account had been activated');
        if(is_null($verSeqNo) && !$account->verSeqNo)return Yii::$app->EC->callback($return, 'this account has not apply to activate');
        $params = array_merge($this->getCommonParams(), [
            'MerUserId' => $account->nanjingUserid,
            'VerSeqNo' => is_null($verSeqNo) ? $account->verSeqNo : $verSeqNo,
            'CheckAmount' => $checkAmount,
        ]);
        $nanjingActivation = new NanjingActivation($params);
        return $this->executeAction($nanjingActivation, function($callback)use($account){
            $activateAccount = new \common\models\parts\trade\recharge\nanjing\message\ActivateAccount([
                'callback' => $callback,
                'accountId' => $account->id,
                'isActive' => NanjingAccount::STATUS_ACTIVE,
            ]);
            $this->sendMessage($activateAccount);
            return true;
        }, $return);
    }

    public function cancelAccount(NanjingAccount $account, $return = 'throw'){
        if(!$account->isAvailable)return Yii::$app->EC->callback($return, 'this account is unavailable');
        $params = array_merge($this->getCommonParams(), [
            'MerUserId' => $account->nanjingUserid,
            'OperType' => 3,
            'IsRate' => 0,
            'MobilePhone' => $account->mobilePhone,
            'CifType' => $account->cifType,
            'CifName' => $account->cifName,
            'IdType' => $account->idType,
            'IdNo' => $account->idNo,
            'AcctType' => $account->acctType,
            'AcctName' => $account->acctName,
            'AcctNo' => $account->acctNo,
            'BranchId' => $account->branchId,
        ]);
        $nanjingAccount = new NJAccount($params);
        return $this->executeAction($nanjingAccount, function($callback)use($account){
            $cancelAccount = new \common\models\parts\trade\recharge\nanjing\message\CancelAccount([
                'callback' => $callback,
                'accountId' => $account->id,
                'cancelDatetime' => Yii::$app->time->fullDate,
                'cancelUnixtime' => Yii::$app->time->unixTime,
            ]);
            $this->sendMessage($cancelAccount);
            return true;
        }, $return);
    }

    public function transOfDraw(DrawTicket $drawTicket, $return = 'throw'){
        if($drawTicket->status != DrawTicket::STATUS_APPLY)return Yii::$app->EC->callback($return, 'incorrect draw ticket status');
        if($drawTicket->isLock)return Yii::$app->EC->callback($return, 'this draw ticket is locked');
        try{
            $drawTicket->lock = true;
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
        $mainAccount = $this->getMainAccount();
        $nanjingAccount = $drawTicket->nanjingAccount;
        $transTime = self::getTransTime();
        $productInfo = "转账{$drawTicket->rmb}元";
        $payParams = [
            'MerUserId' => $mainAccount->nanjingUserid,
            'MerUserAcctNo' => $mainAccount->virAcctNo,
            'MerSellerId' => $nanjingAccount->nanjingUserid,
            'MerSellerAcctNo' => $nanjingAccount->virAcctNo,
            //'MerchantSeqNo' => '',
            'MerchantDateTime' => $transTime,
            'OperType' => '101',
            'PayDevide' => '1',
            'TransAmount' => $drawTicket->rmb,
            //'OrderNo' => '',
            'ProductInfo' => $productInfo,
        ];
        $payment = new NanjingPayment(array_merge($this->getCommonParams(), $payParams));
        $seqNoGenerator = new TransSeqNoGenerator([
            'account' => $drawTicket->userAccount,
            'operation' => $payment,
        ]);
        $seqNo = $seqNoGenerator->id;
        $payment->MerchantSeqNo = $seqNo;
        $payment->OrderNo = $seqNo;
        $payResult = $this->executeAction($payment, function($callback)use($drawTicket){
            $sms = new Sms([
                'mobile' => $drawTicket->userAccount->mobilePhone,
                'signName' => '九大爷平台',
                'templateCode' => 'SMS_100060008',
                'param' => [
                    'account' => $drawTicket->userAccount->userAccount,
                    'rmb' => $drawTicket->rmb,
                ],
            ]);
            (new SmsSender)->send($sms, false);
            return $callback;
        }, false);
        if($payResult instanceof NanjingCallback){
            $payOfDraw = new \common\models\parts\trade\recharge\nanjing\message\PayOfDraw([
                'callback' => $payResult,
                'drawTicketId' => $drawTicket->id,
                'merchantDateTime' => $transTime,
                'merchantSeqNo' => $seqNo,
                'orderNo' => $seqNo,
                'productInfo' => $productInfo,
                'respCode' => $payResult->RespCode,
                'respMsg' => $payResult->RespMsg,
                'transSeqNo' => $payResult->TransSeqNo,
            ]);
            $this->sendMessage($payOfDraw);
            return ($payResult->RespCode == '000000' ? true : $payResult);
        }else{
            return Yii::$app->EC->callback($return, 'request failed');
        }
    }

    public function drawOfDraw(DrawTicket $drawTicket, $return = 'throw'){
        if($drawTicket->status != DrawTicket::STATUS_PASS)return Yii::$app->EC->callback($return, 'incorrect draw ticket status');
        $nanjingAccount = $drawTicket->nanjingAccount;
        $time = self::getTransTime();
        $params = array_merge($this->getCommonParams(), [
            'MerUserId' => $nanjingAccount->nanjingUserid,
            'MerUserAcctNo' => $nanjingAccount->virAcctNo,
            'MerchantDateTime' => $time,
            'TransAmount' => $drawTicket->rmb,
        ]);
        $draw = new NanjingDraw($params);
        $seqNoGenerator = new DirectDrawSeqNoGenerator([
            'account' => $nanjingAccount,
            'operation' => $draw,
        ]);
        $seqNo = $seqNoGenerator->id;
        $draw->MerchantSeqNo = $seqNo;
        $drawResult = $this->executeAction($draw, function($callback){
            return $callback;
        }, false);
        if($drawResult instanceof NanjingCallback){
            $drawOfDraw = new \common\models\parts\trade\recharge\nanjing\message\DrawOfDraw([
                'callback' => $drawResult,
                'drawTicketId' => $drawTicket->id,
                'merchantSeqNo' => $seqNo,
                'merchantDateTime' => $time,
            ]);
            $this->sendMessage($drawOfDraw);
            return $drawResult;
        }else{
            return Yii::$app->EC->callback($return, 'request failed');
        }
    }

    public function queryOfDraw(string $merchantSeqNo, $return = 'throw'){
        $params = array_merge($this->getCommonParams(), [
            'MerchantSeqNo' => $merchantSeqNo,
            'Limit' => 1,
        ]);
        $query = new \common\models\parts\trade\recharge\nanjing\data\NanjingQueryDepositAndDefrayalStatement($params);
        return $this->executeAction($query, function($callback){
            return $callback;
        }, $return);
    }

    public function queryUser(NanjingAccount $account = null, int $record = 0, int $limit = 20, $return = 'throw'){
        $params = $this->getCommonParams();
        $params['Record'] = $record;
        $params['Limit'] = $limit;
        if(!is_null($account))$params['MerUserId'] = $account->nanjingUserid;
        try{
            $account = new NanjingQueryCustomerInfo($params);
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
        return $this->executeAction($account, function($callback){
            return $callback;
        }, $return);
    }

    public function queryBalance(NanjingAccount $account = null, $return = 'throw'){
        $params = $this->getCommonParams();
        if(!is_null($account)){
            $params['MerUserId'] = $account->nanjingUserid;
        }
        $balance = new NanjingQueryBalance($params);
        return $this->executeAction($balance, function($callback){
            return $callback;
        }, $return);
    }

    public function queryDepositAndDraw(NanjingAccount $account = null, $beginDate = null, $endDate = null, $record = 0, $limit = 20, $return = 'throw'){
        if($record < 0 || $limit < 1 || $limit > 20)return Yii::$app->EC->callback($return, 'unavailable record or limit');
        $params = array_merge($this->getCommonParams(), [
            'Record' => $record,
            'Limit' => $limit,
        ]);
        if(!is_null($account))$params['MerUserId'] = $account->nanjingUserid;
        if(!is_null($beginDate))$params['BeginDate'] = $beginDate;
        if(!is_null($endDate))$params['EndDate'] = $endDate;
        $depositAndDraw = new NanjingQueryDepositAndDefrayalStatement($params);
        return $this->executeAction($depositAndDraw, function($callback){
            return $callback;
        }, $return);
    }

    public function sendCaptcha(NanjingAccount $account, int $msgType, array $params = null, $return = 'throw'){
        $MobilePhone = null;
        $TransAmount = null;
        $FeeAmount = null;
        if(!is_null($params))extract($params, EXTR_IF_EXISTS);
        $params = array_merge($this->getCommonParams(), [
            'MsgType' => 3,
        ]);
        $params['MerUserId'] = $account->nanjingUserid;
        if(!is_null($MobilePhone))$params['MobilePhone'] = $MobilePhone;
        if(!is_null($TransAmount))$params['TransAmount'] = $TransAmount;
        if(!is_null($FeeAmount))$params['FeeAmount'] = $FeeAmount;
        $captcha = new NanjingCaptcha($params);
        return $this->executeAction($captcha, function($callback){
            return $callback->VerSeqNo;
        }, $return);
    }

    public function deposit(NanjingAccount $account, float $rmb, string $captcha, string $verSeqNo, $return = 'throw'){
        if($rmb < 1)return Yii::$app->EC->callback($return, 'unavailable rmb');
        $time = self::getTransTime();
        $params = array_merge($this->getCommonParams(), [
            'MerUserId' => $account->nanjingUserid,
            'MerUserAcctNo' => $account->virAcctNo,
            'VerCode' => $captcha,
            'VerSeqNo' => $verSeqNo,
            //'MerchantSeqNo' => '',
            'MerchantDateTime' => $time,
            'TransAmount' => $rmb,
        ]);
        $deposit = new NanjingDeposit($params);
        $seqNoGenerator = new DepositSeqNoGenerator([
            'account' => $account,
            'operation' => $deposit,
        ]);
        $seqNo = $seqNoGenerator->id;
        $deposit->MerchantSeqNo = $seqNo;
        $result = $this->executeAction($deposit, function($callback){
            return $callback;
        }, false);
        if($result instanceof NanjingCallback){
            $deposit = new \common\models\parts\trade\recharge\nanjing\message\Deposit([
                'callback' => $result,
                'nanjingAccountId' => $account->id,
                'userType' => $account->userType,
                'userAccount' => $account->userAccount,
                'merchantSeqNo' => $seqNo,
                'merchantDateTime' => $time,
                'transAmount' => $rmb,
            ]);
            $this->sendMessage($deposit);
            return ($result->RespCode == '000000' ? true : $result);
        }else{
            return Yii::$app->EC->callback($return, 'request failed');
        }
    }

    public function directDraw(NanjingAccount $account, float $rmb, $return = 'throw'){
        if($rmb < 1)return Yii::$app->EC->callback($return, 'unavaialable rmb');
        $time = self::getTransTime();
        $params = array_merge($this->getCommonParams(), [
            'MerUserId' => $account->nanjingUserid,
            'MerUserAcctNo' => $account->virAcctNo,
            'MerchantDateTime' => $time,
            'TransAmount' => $rmb,
        ]);
        $draw = new NanjingDraw($params);
        $seqNoGenerator = new DirectDrawSeqNoGenerator([
            'account' => $account,
            'operation' => $draw,
            'verify' => true,
        ]);
        $seqNo = $seqNoGenerator->id;
        $draw->MerchantSeqNo = $seqNo;
        $result = $this->executeAction($draw, function($callback){
            return $callback;
        }, false);
        if($result instanceof NanjingCallback){
            $directDraw = new \common\models\parts\trade\recharge\nanjing\message\DirectDraw([
                'callback' => $result,
                'nanjingAccountId' => $account->nanjingUserid,
                'userType' => $account->userType,
                'userAccount' => $account->userAccount,
                'merchantSeqNo' => $seqNo,
                'merchantDateTime' => $time,
                'transAmount' => $rmb,
            ]);
            $this->sendMessage($directDraw);
            return ($result->RespCode == '000000' ? true : $result);
        }else{
            return Yii::$app->EC->callback($return, 'request failed');
        }
    }

    public function refundDraw(DrawTicket $ticket, $return = 'throw'){
        if($ticket->status != DrawTicket::STATUS_FAILURE)return Yii::$app->EC->callback($return, 'incorrect ticket status');
        $payBalance = \common\ActiveRecord\NanjingPayBalanceAR::findOne([
            'operation_type' => self::OPERATION_PAY_OF_DRAW,
            'corresponding_id' => $ticket->id,
        ]);
        if(!$payBalance)return Yii::$app->EC->callback($return, 'unable to find pay record');
        $transTime = $this->getTransTime();
        $params = array_merge($this->getCommonParams(), [
            //'MerchantSeqNo' => '',
            'MerchantDateTime' => $transTime,
            'OrgMerchantSeqNo' => $payBalance->merchant_seq_no,
        ]);
        $refund = new NanjingRefund($params);
        $seqNoGenerator = new RefundSeqNoGenerator([
            'account' => $ticket->nanjingAccount,
            'operation' => $refund,
            'verify' => true,
        ]);
        $seqNo = $seqNoGenerator->id;
        $refund->MerchantSeqNo = $seqNo;
        $result = $this->executeAction($refund, function($callback){
            return $callback;
        }, $return);
        if($result instanceof NanjingCallback && $result->RespCode == '000000'){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                Yii::$app->RQ->AR(new \common\ActiveRecord\NanjingRefundAR)->insert([
                    'operation_type' => self::OPERATION_REFUND_OF_DRAW,
                    'corresponding_id' => $ticket->id,
                    'nanjing_pay_balance_id' => $payBalance->id,
                    'org_merchant_seq_no' => $payBalance->merchant_seq_no,
                    'is_back_fee' => 1,
                    'merchant_date_time' => $transTime,
                    'merchant_seq_no' => $seqNo,
                    'status' => 1,
                    'resp_code' => $result->RespCode,
                    'resp_msg' => $result->RespMsg,
                    'trans_seq_no' => $result->TransSeqNo,
                ]);
                Yii::$app->RQ->AR(\common\ActiveRecord\UserDrawAR::findOne($ticket->id))->update([
                    'refund_datetime' => date('Y-m-d H:i:s'),
                    'refund_unixtime' => time(),
                ]);
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
                return Yii::$app->EC->callback($return, $e);
            }
        }
        return $result;
    }

    public function gatewayDeposit($merchantSeqNo, $acctType, $transAmount, $return = 'throw'){
        if(empty($merchantSeqNo) || !in_array($acctType, [11, 12]) || $transAmount <= 0)return Yii::$app->EC->callback($return, 'unavailable request data');
        $params = array_merge($this->getCommonParams(), [
            'MerchantSeqNo' => $merchantSeqNo,
            'MerchantDateTime' => ($transTime = self::getTransTime()),
            'OperType' => '300',
            'AcctType' => $acctType,
            'MerUserId' => $this->mainAccount->nanjingUserid,
            'MerUserAcctNo' => $this->mainAccount->virAcctNo,
            'TransAmount' => $transAmount,
        ]);
        $gatewayPayment = new NJGatewayPayment($params);
        return $this->executeAction($gatewayPayment, function($callback){
            return $callback;
        }, $return);
    }

    public function queryGatewayDeposit($merchantSeqNo){
        $params = array_merge($this->getCommonParams(), [
            'MerchantSeqNo' => $merchantSeqNo,
        ]);
        $depositAndDraw = new NanjingQueryDepositAndDefrayalStatement($params);
        return $this->executeAction($depositAndDraw, function($callback){
            return $callback;
        });
    }

    public function queryAccountDetail(string $from, string $to, AccountAbstract $account = null){
        $params = [
            'BeginDate' => $from,
            'EndDate' => $to,
            'Limit' => 20,
        ];
        if(!is_null($account)){
            $params['MerUserId'] = $account->merUserId;
            $params['VirAcctNo'] = $account->virAcctNo;
        }
        $queryAccountStatement = new NanjingQueryAccountStatement(array_merge($params, $this->getCommonParams()));
        return $this->executeAction($queryAccountStatement, function($callback){
            return $callback;
        });
    }

    public static function getTransTime(){
        return date('YmdHis');
    }

    private function executeAction(Base $action, callable $operationAfterSuccess, $return = 'throw'){
        try{
            $callback = $action->execute();
            if($callback === false)throw new \Exception('posting data failed');
            if($callback->isSuccess()){
                Yii::info($callback, __METHOD__);
                return call_user_func($operationAfterSuccess, $callback);
            }else{
                return $callback;
            }
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
    }

    private function sendMessage($action){
        $message = new Message($action);
        Yii::$app->amqp->publish($message);
    }
}
