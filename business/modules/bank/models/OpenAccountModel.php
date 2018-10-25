<?php
namespace business\modules\bank\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\BankCodeTinyAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use business\models\parts\trade\nanjing\BusinessAccount;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;
use common\models\parts\trade\recharge\nanjing\bank\Branch;

class OpenAccountModel extends Model{

    const SCE_ADD_CARD = 'add_card';

    public $mobile_phone;
    public $id_type;
    public $id_no;
    public $acct_type;
    public $acct_name;
    public $acct_no;
    public $branch_id;

    public function scenarios(){
        return [
            self::SCE_ADD_CARD => [
                'mobile_phone',
                'id_type',
                'id_no',
                'acct_type',
                'acct_name',
                'acct_no',
                'branch_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['mobile_phone', 'id_type', 'id_no', 'acct_type', 'acct_name', 'acct_no', 'branch_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['mobile_phone'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['id_type'],
                'in',
                'range' => ['1', '2', '3', '4', '5', '6', '7', 'm', 'n', 'p', 'q', 'r'],
                'message' => 9002,
            ],
            [
                ['id_no', 'acct_no'],
                'string',
                'length' => [1, 32],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['acct_type'],
                'in',
                'range' => ['0', '1'],
                'message' => 9002,
            ],
            [
                ['acct_name'],
                'string',
                'length' => [1, 100],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['branch_id'],
                'exist',
                'targetClass' => BankCodeTinyAR::className(),
                'targetAttribute' => 'id',
                'message' => 9002,
            ],
        ];
    }

    public function addCard(){
        $params = [
            'MobilePhone' => $this->mobile_phone,
            'CifType' => $this->acct_type == '1' ? '0' : '1',
            'CifName' => $this->acct_name,
            'IdType' => $this->id_type,
            'IdNo' => $this->id_no,
            'AcctType' => $this->acct_type,
            'AcctName' => $this->acct_name,
            'AcctNo' => $this->acct_no,
            'BranchId' => (new Branch(['id' => $this->branch_id]))->branchId,
        ];
        $businessAccount = new BusinessAccount([
            'id' => Yii::$app->user->id,
        ]);
        $nanjing = new Nanjing;
        $result = $nanjing->createAccount($params, $businessAccount, false);
        if($result === true){
            return [
                'is_success' => true,
                'err_msg' => '',
            ];
        }elseif($result instanceof NanjingCallback){
            return [
                'is_success' => false,
                'err_msg' => $result->RespMsg,
            ];
        }else{
            $this->addError('add', 13391);
            return false;
        }
    }

    public static function isNanjingAccountExist(){
        if(Yii::$app->user->isGuest)return false;
        $businessAccount = new BusinessAccount([
            'id' => Yii::$app->user->id,
        ]);
        if($businessAccount->getNanjingAccount(false)){
            return true;
        }else{
            return false;
        }
    }
}
