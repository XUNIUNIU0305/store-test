<?php
namespace common\models\parts\trade\recharge\nanjing\bank;

use Yii;
use common\models\ObjectAbstract;
use common\ActiveRecord\BankCodeTinyAR;
use yii\base\InvalidConfigException;

class Branch extends ObjectAbstract{

    public $id;
    public $branchId;

    protected $AR;

    private $_bank;

    public function init(){
        if($this->id){
            $this->AR = BankCodeTinyAR::findOne($this->id);
        }elseif($this->branchId){
            $this->AR = BankCodeTinyAR::findOne(['bank_id' => $this->branchId]);
        }
        if(!$this->AR)throw new InvalidConfigException('unavailable AR');
        $this->id = $this->AR->id;
        $this->branchId = $this->AR->bank_type;
    }

    public function getBank(){
        if(is_null($this->_bank)){
            $this->_bank = new Bank([
                'type' => $this->AR->bank_type,
            ]);
        }
        return $this->_bank;
    }

    protected function _gettingList() : array{
        return [
            'bankId',
            'bankType',
            'areaCode',
            'bankName',
        ];
    }

    protected function _settingList() : array{
        return [];
    }
}
