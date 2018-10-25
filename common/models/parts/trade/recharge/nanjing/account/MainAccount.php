<?php
namespace common\models\parts\trade\recharge\nanjing\account;

use Yii;
use common\models\parts\trade\recharge\nanjing\Nanjing;

class MainAccount extends NanjingAccount{

    public $env;

    public function init(){
        $this->AR = new \stdClass;
        if($this->env == Nanjing::ENV_PROD){
            $mainAccount = Yii::$app->params['NANJING_Main_Account'];
            $this->AR->nanjing_userid = $mainAccount['nanjing_userid'];
            $this->AR->mobile_phone = $mainAccount['mobile_phone'];
            $this->AR->cif_type = $mainAccount['cif_type'];
            $this->AR->cif_name = $mainAccount['cif_name'];
            $this->AR->id_type = $mainAccount['id_type'];
            $this->AR->id_no = $mainAccount['id_no'];
            $this->AR->acct_type = $mainAccount['acct_type'];
            $this->AR->acct_name = $mainAccount['acct_name'];
            $this->AR->acct_no = $mainAccount['acct_no'];
            $this->AR->bank_type = $mainAccount['bank_type'];
            $this->AR->branch_id = $mainAccount['branch_id'];
            $this->AR->vir_acct_no = $mainAccount['vir_acct_no'];
            $this->AR->vir_acct_name = $mainAccount['vir_acct_name'];
        }else{
            $this->AR->nanjing_userid = 'main';
            $this->AR->mobile_phone = '12345678901';
            $this->AR->cif_type = '1';
            $this->AR->cif_name = '2108787741';
            $this->AR->id_type = 'm';
            $this->AR->id_no = '050274317';
            $this->AR->acct_type = '0';
            $this->AR->acct_name = '2108787741';
            $this->AR->acct_no = '12010120000000343';
            $this->AR->bank_type = '313301008887';
            $this->AR->branch_id = '313290040135';
            $this->AR->vir_acct_no = '030121000000054300000046';
            $this->AR->vir_acct_name = '2108787741';
        }
        $this->AR->user_id = 0;
        $this->AR->user_type = AccountAbstract::ACCOUNT_TYPE_ADMIN;
        $this->AR->user_account = 0;
        $this->AR->is_active = 1;
        $this->AR->ver_seq_no = '';
        $this->id = 0;
    }

    protected function _settingList() : array{
        return [];
    }
}
