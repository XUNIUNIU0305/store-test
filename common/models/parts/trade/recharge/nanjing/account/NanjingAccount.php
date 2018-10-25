<?php
namespace common\models\parts\trade\recharge\nanjing\account;

use Yii;
use common\models\ObjectAbstract;
use common\ActiveRecord\NanjingAccountAR;
use yii\base\InvalidConfigException;
use common\models\parts\trade\recharge\nanjing\bank\Bank;
use common\models\parts\trade\recharge\nanjing\bank\Branch;

class NanjingAccount extends ObjectAbstract{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE = 0;

    public $id;

    private $_bank;
    private $_branch;

    public function init(){
        if(!$this->AR = NanjingAccountAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
    }

    public static function newInstance($account, $accountType = null, $return = 'throw'){
        if(is_null($accountType)){
            $nanjingUserid = $account;
        }else{
            $nanjingUserid = (string)$accountType . (string)$account;
        }
        if($AR = NanjingAccountAR::findOne([
            'nanjing_userid' => $nanjingUserid,
            'is_available' => self::STATUS_AVAILABLE,
        ])){
            return new NanjingAccount(['id' => $AR->id]);
        }else{
            return Yii::$app->EC->callback($return, 'unavailable account info');
        }
    }

    public function getCoveredAcctNo(string $coveredTag = '****', string $split = ''){
        $acctNo = $this->AR->acct_no;
        if(strlen($acctNo) >= 12){
            $textDisplayedLength = 4;
        }else{
            $textDisplayedLength = 2;
        }
        $textFront = substr($acctNo, 0, $textDisplayedLength);
        $textEnd = substr($acctNo, $textDisplayedLength * -1);
        return ($textFront . $split . $coveredTag . $split . $textEnd);
    }

    public function getCoveredAcctName(string $coveredTag = '**', string $split = ''){
        $acctName = $this->AR->acct_name;
        $acctNameLength = mb_strlen($acctName, Yii::$app->charset);
        if($acctNameLength <= 1){
            return ($acctName . $coveredTag);
        }
        if($acctNameLength == 2){
            $textFront = mb_substr($acctName, 0, 1, Yii::$app->charset);
            $textEnd = '';
            $coveredLength = 1;
        }elseif($acctNameLength <= 6){
            $textFront = mb_substr($acctName, 0, 1, Yii::$app->charset);
            $textEnd = mb_substr($acctName, -1, null, Yii::$app->charset);
            $coveredLength = $acctNameLength - 2;
        }else{
            $textFront = mb_substr($acctName, 0, 2, Yii::$app->charset);
            $textEnd = mb_substr($acctName, -2, null, Yii::$app->charset);
            $coveredLength = $acctNameLength - 4;
        }
        return ($textFront . $split . str_repeat($coveredTag, $coveredLength) . $split . $textEnd);
    }

    public function getBank(){
        if(is_null($this->_bank)){
            $this->_bank = new Bank([
                'type' => $this->AR->bank_type,
            ]);
        }
        return $this->_bank;
    }

    public function getBranch(){
        if(is_null($this->_branch)){
            $this->_branch = new Branch([
                'branchId' => $this->branch_id,
            ]);
        }
        return $this->_branch;
    }

    public function getVerSeqNo(){
        if($this->AR->ver_seq_no && $this->AR->ver_seq_no_unixtime){
            if($this->AR->ver_seq_no_unixtime + 7200 > time()){
                return $this->AR->ver_seq_no;
            }else{
                return '';
            }
        }else{
            return '';
        }
    }

    public function getIsActive(){
        return $this->AR->is_active ? true : false;
    }

    public function getIsAvailable(){
        return $this->AR->is_available ? true : false;
    }

    protected function _gettingList() : array{
        return [
            'nanjingUserid',
            'userId',
            'userType',
            'userAccount',
            'mobilePhone',
            'cifType',
            'cifName',
            'idType',
            'idNo',
            'acctType',
            'acctName',
            'bankType',
            'acctNo',
            'branchId',
            'virAcctNo',
            'virAcctName',
            'createDatetime',
            'createUnixtime',
        ];
    }

    protected function _settingList() : array{
        return [
            'verSeqNo',
            'verSeqNoUnixtime',
        ];
    }
}
