<?php
namespace common\models\parts\trade\recharge\nanjing\handler;

use Yii;
use yii\base\InvalidConfigException;
use common\ActiveRecord\UserDrawAR;
use common\models\parts\basic\UniqueIdGeneratorAbstract;
use common\models\parts\trade\recharge\nanjing\data\Base;
use common\models\parts\trade\recharge\nanjing\data\NanjingPayment;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;
use common\models\parts\trade\recharge\nanjing\account\NanjingAccount;

class SeqNoGenerator extends UniqueIdGeneratorAbstract{

    public $account;
    public $operation;
    public $verify;

    protected $_verify = true;

    private $_accountNo;
    private $_accountType;
    private $_operationNo;
    private $_operationType;

    public function init(){
        if($this->account instanceof AccountAbstract){
            $this->_accountNo = $this->convertAccountNo($this->account->getUserAccount());
            $this->_accountType = $this->account->getUserType();
        }elseif($this->account instanceof NanjingAccount){
            $this->_accountNo = $this->convertAccountNo($this->account->userAccount);
            $this->_accountType = $this->account->userType;
        }else{
            throw new InvalidConfigException('unavailable account');
        }
        if($this->operation instanceof Base){
            if(!is_null($this->verify))$this->_verify = (bool)$this->verify;
            $this->_operationType = '';
            $this->_operationNo = $this->convertOperationNo($this->operation->getOperation());
            if($this->operation instanceof NanjingPayment){
                $this->_operationNo = $this->operation->OperType;
            }
        }else{
            $this->_operationNo = '';
            $this->_operationType = '';
        }
        parent::init();
    }

    public function getId(){
        if($this->_verify){
            return parent::getId();
        }else{
            return $this->generateId();
        }
    }

    protected function convertAccountNo($account){
        if(strlen($account) >= 9)return $account;
        while(strlen($account) < 9){
            $account = '0' . $account;
        }
        return $account;
    }

    protected function convertOperationNo($operation){
        return (string)substr($operation, 2);
    }

    protected function getActiveRecord(){
        return new UserDrawAR;
    }

    protected function getFieldName(){
        return 'draw_number';
    }

    protected function generateId(){
        $randomId = parent::generateId();
        return ($this->_operationNo . $randomId . $this->_accountType . $this->_accountNo);
    }
}
