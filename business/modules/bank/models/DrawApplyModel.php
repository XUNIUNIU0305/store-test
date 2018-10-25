<?php
namespace business\modules\bank\models;

use Yii;
use common\models\Model;
use business\models\parts\SmsCaptcha;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicketHandler;
use business\models\parts\trade\nanjing\BusinessAccount;

class DrawApplyModel extends Model{

    const SCE_CREATE_APPLY = 'create_apply';

    public $rmb;
    public $captcha;
    public $password;

    public function scenarios(){
        return [
            self::SCE_CREATE_APPLY => [
                'rmb',
                'captcha',
                'password',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['rmb', 'captcha', 'password'],
                'required',
                'message' => 9001,
            ],
            [
                ['rmb'],
                'business\modules\bank\validators\RmbValidator',
                'message' => 9002,
            ],
            [
                ['password'],
                'string',
                'length' => [1, 100],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['captcha'],
                'business\modules\bank\validators\CaptchaValidator',
                'mobile' => Yii::$app->BusinessUser->account->mobile,
                'message' => 9005,
            ],
        ];
    }

    public function createApply(){
        if(date('Y-m-d') . ' 09:00:00' > Yii::$app->time->fullDate || date('Y-m-d') . ' 16:30:00' < Yii::$app->time->fullDate){
            $this->addError('createApply', 13431);
            return false;
        }
        if(!Yii::$app->security->validatePassword($this->password, Yii::$app->user->identity->passwd)){
            $this->addError('createApply', 13422);
            return false;
        }
        $businessAccount = new BusinessAccount([
            'id' => Yii::$app->user->id,
        ]);
        if(Yii::$app->user->id == 1473){
            $this->addError('createApply', 13401);
            return false;
        }
        if(!$nanjingAccount = $businessAccount->getNanjingAccount(false)){
            $this->addError('createApply', 13401);
            return false;
        }
        if(!$nanjingAccount->isActive){
            $this->addError('createApply', 13424);
            return false;
        }
        if(Yii::$app->BusinessUser->account->wallet->rmb < $this->rmb){
            $this->addError('createApply', 13423);
            return false;
        }
        if(DrawTicketHandler::create((float)$this->rmb, $businessAccount, false)){
            return true;
        }else{
            $this->addError('createApply', 13421);
            return false;
        }
    }
}
