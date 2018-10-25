<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\models\parts\wechat\WechatAbstract;
use common\models\parts\wechat\UrlParamCrypt;
use common\components\handler\Handler;

class WechatModel extends Model{

    const SCE_SCAN_HANDLE = 'scan_handle';

    public $state;
    public $code;

    public function scenarios(){
        return [
            self::SCE_SCAN_HANDLE => [
                'state',
                'code',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['state', 'code'],
                'required',
                'message' => 9001,
            ],
        ];
    }

    public function scanHandle(){
        if($state = (new UrlParamCrypt)->decrypt(str_replace(' ', '+', $this->state))){
            $siteHandler = [
                WechatAbstract::SITE_CUSTOM => Yii::$app->params['CUSTOM_Hostname'] . '/wechat/user',
            ];
            if(isset($state['site']) && isset($siteHandler[$state['site']])){
                return $siteHandler[$state['site']] . '?' . Handler::implodeUrlParams(['state' => urlencode(str_replace(' ', '+', $this->state)), 'code' => urlencode($this->code)]);
            }
        }
        $this->addError('scanHandle', 9002);
        return false;
    }
}
