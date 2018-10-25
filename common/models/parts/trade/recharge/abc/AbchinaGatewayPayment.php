<?php
namespace common\models\parts\trade\recharge\abc;

use Yii;
use yii\base\Object;
use common\models\parts\trade\recharge\abc\account\AbchinaAccount;
use common\models\parts\trade\recharge\RechargeApply;
use common\ActiveRecord\AbchinaOrderAR;

class AbchinaGatewayPayment extends Object{

    public $config;

    private $_rechargeId;
    private $_billNo;
    private $_account; //未赋值
    private $_accountType; //未赋值
    private $_dealerNo;
    private $_totalAmount;
    private $_productData;
    private $_settlementAmount;
    private $_loginName;

    private static $_abc;

    public function init(){
        $this->_rechargeId = $this->config['rechargeId'] ?? null;
        $this->_billNo = $this->config['billNo'] ?? null;
        if(isset($this->config['account']) && isset($this->config['accountType']))$this->initializeDealerNo();
        $this->_totalAmount = $this->config['totalAmount'] ?? null;
        $this->_productData = $this->config['productData'] ?? null;
        $this->_settlementAmount = $this->config['settlementAmount'] ?? null;
        $this->_loginName = $this->config['loginName'] ?? null;
        self::$_abc = new Abc;
    }

    public function generatePayUrl(){
        $billId = self::$_abc->addOrder([
            'BillNO' => $this->_billNo,
            'DealerNO' => $this->_dealerNo,
            'TotalAmount' => $this->_totalAmount,
            'ProductData' => $this->_productData,
            'SettlementAmount' => $this->_settlementAmount,
            'LoginName' => $this->_loginName,
        ]);
        if(!$billId || !Yii::$app->RQ->AR(new AbchinaOrderAR)->insert([
            'recharge_apply_id' => $this->_rechargeId,
            'bill_id' => $billId,
            'bill_no' => $this->_billNo,
            'dealer_no' => $this->_dealerNo,
            'total_amount' => $this->_totalAmount,
            'settlement_amount' => $this->_settlementAmount,
            'login_name' => $this->_loginName,
        ]))return false;
        return self::$_abc->generatePayUrl($this->_dealerNo, $billId, Yii::$app->params['ABCHINA_Callback_Url'] . '?q=' . (new \custom\models\parts\UrlParamCrypt)->encrypt($this->_totalAmount));
    }

    private function initializeDealerNo(){
        switch($this->config['accountType']){
        case RechargeApply::USER_TYPE_CUSTOMER:
            $accountType = AbchinaAccount::ACCOUNT_TYPE_CUSTOM;
            break;

        case RechargeApply::USER_TYPE_SUPPLIER:
            $accountType = AbchinaAccount::ACCOUNT_TYPE_SUPPLY;
            break;

        case RechargeApply::USER_TYPE_ADMINISTRATOR:
            $accountType = AbchinaAccount::ACCOUNT_TYPE_ADMIN;
            break;

        default:
            $accountType = null;
            break;
        }
        try{
            $abchinaAccount = AbchinaAccount::generate($this->config['account'], $accountType);
            $this->_dealerNo = $abchinaAccount->ebiz_dealer_no;
        }catch(\Exception $e){

        }
    }
}
