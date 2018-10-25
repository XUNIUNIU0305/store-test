<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 14:35
 */

namespace custom\models;


use common\models\Model;

use common\models\parts\sms\SmsSender;
use custom\models\parts\sms\Sms;
use custom\models\parts\sms\SmsCaptcha;

class SmsModel extends Model
{

    //发送验证码
    const SCE_SEND = "send_sms";
    public $mobile;


    public $type;

    private $smsType=[
        0=>'SMS_58285247',//注册账号
        1=>'SMS_58200292',//找回密码
        2=>'SMS_58175300',//绑定手机
        3=>'SMS_58200293',//改绑手机
        4=>'SMS_59805070',//修改资料
        5=>'SMS_61725380',//提交质保系统，给上级督导发送短信
        6=>'SMS_95490059',//质保单查询
    ];


    //配置场景
    public function scenarios()
    {
        return [
            self::SCE_SEND => ['mobile','type'],
         ];
    }

    //设置规则
    public function rules()
    {
        return [
            [
                ['mobile'],
                'required',
                'message' => 9001
            ],
            [
                ['mobile'],
                'common\validators\custom\AccountMobileValidator',
                'type'=>$this->type,
                'message'=>3259,
                'message_exists'=>3257,
            ],
            [
                ['type'],
                'default',
                'value'=>0
            ],
            [
                ['type'],
                'in',
                'range' => [0,1,2,3,4,5,6],
                'message' => 3258,
            ],
        ];
    }


    //发送短信
    public function sendSms(array $param=[])
    {
        $smsSender = new SmsSender();
        $sms = new SmsCaptcha([
            'mobile' => [$this->mobile],
            'signName' => '九大爷平台',
            'templateCode' => $this->smsType[$this->type],
            'param' => array_merge(['captcha' => rand(100000, 999999),],$param),
        ]);

        //print_r($sms);exit();

        if ($smsSender->send($sms,false)) {
            return true;
        }
        $this->addError('sendSms', 3251);
        return false;
    }
}