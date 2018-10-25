<?php
namespace admin\modules\nanjing\models;

use Yii;
use common\models\Model;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;
use admin\modules\nanjing\models\parts\UserAccountHandler;

class FundManagementModel extends Model{

    const SCE_SEND_CAPTCHA = 'send_captcha';
    const SCE_DEPOSIT_TO_MAIN_ACCOUNT = 'deposit_to_main_account';
    const SCE_DRAW_FROM_MAIN_ACCOUNT = 'draw_from_main_account';
    const SCE_GET_LIST = 'get_list';
    const SCE_GET_BALANCE = 'get_balance';
    const SCE_GET_ALL_USERS_FUND = 'get_all_users_fund';

    public $rmb;
    public $captcha;
    public $ver_seq_no;
    public $begin_date;
    public $end_date;
    public $current_record;
    public $record_size;
    public $force;

    public function scenarios(){
        return [
            self::SCE_SEND_CAPTCHA => [],
            self::SCE_DEPOSIT_TO_MAIN_ACCOUNT => [
                'rmb',
                'captcha',
                'ver_seq_no',
            ],
            self::SCE_DRAW_FROM_MAIN_ACCOUNT => [
                'rmb',
            ],
            self::SCE_GET_LIST => [
                'begin_date',
                'end_date',
                'current_record',
                'record_size',
            ],
            self::SCE_GET_BALANCE => [
                'force',
            ],
            self::SCE_GET_ALL_USERS_FUND => [
                'force',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_record'],
                'default',
                'value' => 0,
            ],
            [
                ['record_size'],
                'default',
                'value' => 20,
            ],
            [
                ['force'],
                'default',
                'value' => 0,
            ],
            [
                ['rmb', 'captcha', 'ver_seq_no', 'current_record', 'record_size'],
                'required',
                'message' => 9001,
            ],
            [
                ['rmb'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['captcha', 'ver_seq_no'],
                'string',
                'length' => [1, 100],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['begin_date'],
                'date',
                'format' =>'php:Y-m-d',
                'message' => 9002,
            ],
            [
                ['end_date'],
                'date',
                'format' => 'php:Y-m-d',
                'message' => 9002,
            ],
            [
                ['current_record'],
                'integer',
                'min' => 0,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['record_size'],
                'integer',
                'min' => 1,
                'max' => 20,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['force'],
                'in',
                'range' => [0, 1],
                'message' => 9002,
            ],
        ];
    }

    public function getAllUsersFund(){
        if($this->force){
            return $this->queryUsersFund();
        }else{
            if($fund = Yii::$app->cache->get('__usersFund')){
                return $fund;
            }else{
                return $this->queryUsersFund();
            }
        }
    }

    protected function queryUsersFund(){
        $fund = UserAccountHandler::getUsersFund(3);
        $fund['time'] = date('Y-m-d H:i:s');
        Yii::$app->cache->set('__usersFund', $fund, 3600);
        return $fund;
    }

    public function getBalance(){
        if($this->force){
            if($balance = $this->requestBalance()){
                Yii::$app->cache->set('__mainAccountBalance', $balance, 60);
                return $balance;
            }else{
                $this->addError('getBalance', 5361);
                return false;
            }
        }else{
            if($balance = Yii::$app->cache->get('__mainAccountBalance')){
                return $balance;
            }else{
                if($balance = $this->requestBalance()){
                    Yii::$app->cache->set('__mainAccountBalance', $balance, 60);
                    return $balance;
                }else{
                    $this->addError('getBalance', 5361);
                    return false;
                }
            }
        }
    }

    protected function requestBalance(){
        $nanjing = new Nanjing;
        $mainAccount = $nanjing->mainAccount;
        if($result = $nanjing->queryBalance($mainAccount, false)){
            return [
                'rmb' => $result->List[0]['Amount'],
                'time' => Yii::$app->time->fullDate,
            ];
        }else{
            return false;
        }
    }

    public function getList(){
        $beginDate = $this->begin_date ? date('Ymd', strtotime($this->begin_date)) : null;
        $endDate = $this->end_date ? date('Ymd', strtotime($this->end_date)) : null;
        $nanjing = new Nanjing;
        $mainAccount = $nanjing->mainAccount;
        $result = $nanjing->queryDepositAndDraw($mainAccount, $beginDate, $endDate, $this->current_record, $this->record_size, false);
        if($result instanceof NanjingCallback){
            return [
                'is_success' => $result->RespCode == '000000' ? true : false,
                'err_msg' => $result->RespCode == '000000' ? '' : $result->RespMsg,
                'list' => is_array($list = $result->List) ? array_map(function($list){
                    unset($list['MerUserId']);
                    unset($list['UserAcctNo']);
                    unset($list['RcvAcctNo']);
                    return $list;
                }, $list) : [],
            ];
        }else{
            $this->addError('getList', 5351);
            return false;
        }
    }

    public function drawFromMainAccount(){
        $nanjing = new Nanjing;
        $mainAccount = $nanjing->mainAccount;
        $result = $nanjing->directDraw($mainAccount, $this->rmb, false);
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
            $this->addError('drawFromMainAccount', 5341);
            return false;
        }
    }

    public function depositToMainAccount(){
        $nanjing = new Nanjing;
        $mainAccount = $nanjing->mainAccount;
        $result = $nanjing->deposit($mainAccount, (float)$this->rmb, $this->captcha, $this->ver_seq_no, false);
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
            $this->addError('depositToMainAccount', 5331);
            return false;
        }
    }

    public function sendCaptcha(){
        $nanjing = new Nanjing;
        $mainAccount = $nanjing->mainAccount;
        $result = $nanjing->sendCaptcha($mainAccount, 3, null, false);
        if(is_string($result)){
            return [
                'is_success' => true,
                'err_msg' => '',
                'ver_seq_no' => $result,
            ];
        }elseif($result instanceof NanjingCallback){
            return [
                'is_success' => false,
                'err_msg' => $result->RespMsg,
                'ver_seq_no' => '',
            ];
        }else{
            $this->addError('sendCaptcha', 5321);
            return false;
        }
    }
}
