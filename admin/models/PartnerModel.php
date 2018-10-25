<?php
namespace admin\models;

use Yii;
use common\models\Model;
use common\models\parts\partner\PartnerPromoter;
use common\models\parts\partner\UrlParamCrypt;
use admin\components\handler\PartnerApplyHandler;
use admin\components\handler\TradeHandler;
use admin\components\handler\AdminRechargeApplyHandler;
use admin\models\parts\trade\RechargeMethod;
use custom\models\parts\wechat\Wechat;
use common\models\parts\sms\SmsSender;
use admin\models\parts\sms\SmsCaptcha;
use common\models\parts\partner\PartnerApply;
use common\ActiveRecord\CustomUserAR;

class PartnerModel extends Model{

    public $q;
    public $mobile;
    public $passwd;
    public $confirm_passwd;
    public $captcha;
    public $a;

    const SCE_DISPLAY_PAGE = 'display_page';
    const SCE_APPLY = 'apply';
    const SCE_SEND_CAPTCHA = 'send_captcha';
    const SCE_GET_PROMOTER = 'get_promoter';
    const SCE_GET_REGISTERCODE = 'get_registercode';

    public function scenarios(){
        return [
            self::SCE_DISPLAY_PAGE => [
                'q',
            ],
            self::SCE_APPLY => [
                'q',
                'mobile',
                'captcha',
                'passwd',
                'confirm_passwd',
            ],
            self::SCE_SEND_CAPTCHA => [
                'q',
                'mobile',
            ],
            self::SCE_GET_PROMOTER => [
                'q',
            ],
            self::SCE_GET_REGISTERCODE => [
                'a',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['q', 'mobile', 'captcha', 'a', 'passwd', 'confirm_passwd'],
                'required',
                'message' => 9001,
            ],
            [
                ['q'],
                'common\validators\partner\QValidator',
                'message' => 5272,
                'unavailable' => 5273,
            ],
            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 5274,
                'tooBig' => 5274,
                'message' => 5274,
            ],
            [
                ['passwd'],
                'string',
                'length' => [8, 40],
                'tooShort' => 5278,
                'tooLong' => 5279,
                'message' => 9002,
            ],
            [
                ['confirm_passwd'],
                'required',
                'requiredValue' => $this->passwd,
                'message' => 9001,
            ],
            [
                ['captcha'],
                'common\validators\partner\SmsValidator',
                'mobile' => $this->mobile,
                'message' => 5282,
            ],
            [
                ['a'],
                'common\validators\partner\AValidator',
                'message' => 5277,
            ],
        ];
    }

    public function displayPage(){
        if(Yii::$app->session->get('__wechat_public_openid', false)){
            return true;
        }else{
            if($code = Yii::$app->session->get('__wechat_code', false)){
                Yii::$app->session->remove('__wechat_code');
            }else{
                $this->addError('displayPage', 5276);
                return false;
            }
            $wechat = new Wechat([
                'site' => Wechat::SITE_CUSTOM,
                'appId' => Yii::$app->params['WECHAT_Public_Appid'],
                'appSecret' => Yii::$app->params['WECHAT_Public_Appsecret'],
            ]);
            $accessToken = $wechat->getAccessToken($code);
            if(!isset($accessToken['openid'])){
                $this->addError('displayPage', 5276);
                return false;
            }
            Yii::$app->session->set('__wechat_public_openid', $accessToken['openid']);
            return true;
        }
    }

    public function apply(){
        $promoterId = (new UrlParamCrypt)->decrypt($this->q);
        $partnerPromoter = new PartnerPromoter(['id' => $promoterId]);
        $rechargeMethod = new RechargeMethod(['method' => RechargeMethod::METHOD_WX_INWECHAT]);
        if(CustomUserAR::findOne(['mobile' => $this->mobile])){
            $this->addError('apply', 5280);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $partnerApply = PartnerApplyHandler::create($partnerPromoter, [
                'mobile' => $this->mobile,
                'passwd' => $this->passwd,
            ]);
            $trade = TradeHandler::createPartnerTrade($partnerApply);
            $rechargeApply = AdminRechargeApplyHandler::create($trade->totalFee, $rechargeMethod, $trade);
            $rechargeUrl = $rechargeApply->generateRechargeUrl();
            $callback = ['url' => $rechargeUrl];
            Yii::$app->session->set('__partner_apply_id', (new UrlParamCrypt)->encrypt($partnerApply->id));
            $transaction->commit();
            return $callback;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('apply', 5275);
            return false;
        }
    }

    public function sendCaptcha(){
        if(CustomUserAR::findOne(['mobile' => $this->mobile])){
            $this->addError('sendCaptcha', 5280);
            return false;
        }
        $smsSender = new SmsSender;
        $sms = new SmsCaptcha([
            'mobile' => [$this->mobile],
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_78610075',
            'param' => ['captcha' => rand(100000, 999999)],
        ]);
        if($smsSender->send($sms, false)){
            return true;
        }else{
            $this->addError('sendCaptcha', 5281);
            return false;
        }
    }

    public function getPromoter(){
        $promoterId = (new UrlParamCrypt)->decrypt($this->q);
        $promoter = new PartnerPromoter(['id' => $promoterId]);
        switch($promoter->type){
            case PartnerPromoter::TYPE_BUSINESS:
                $info = [
                    'type' => $promoter->type,
                    'title' => $promoter->user->area->name,
                    'remark' => $promoter->title,
                ];
                break;

            case PartnerPromoter::TYPE_CUSTOM:
                $info = [
                    'type' => $promoter->type,
                    'title' => $promoter->user->nickName ? : $promoter->user->account,
                ];
                break;

            default:
                $this->addError('getPromoter', 5272);
                return false;
        }
        return $info;
    }

    public function getRegistercode(){
        $partnerApplyId = (new UrlParamCrypt)->decrypt($this->a);
        $partnerApply = new PartnerApply(['id' => $partnerApplyId]);
        $registerCode = $partnerApply->registerCode ? : '';
        $registerCode = $partnerApply->registerCode ? $partnerApply->registerCode->account : '';
        return [
            'code' => $registerCode,
        ];
    }
}
