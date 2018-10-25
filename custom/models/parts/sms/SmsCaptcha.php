<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 16:18
 */

namespace custom\models\parts\sms;


use common\ActiveRecord\CustomSmsAR;
use common\models\parts\sms\SmsCaptchaAbstract;


class SmsCaptcha extends SmsCaptchaAbstract
{
    /**
     * 获取基于站点的短信签名
     *
     * @return array
     */
    protected static function getSiteBasedSignNames()
    {
        return [];
    }

    /**
     * 获取基于站点的短信模板
     *
     * @return array
     */
    protected static function getSiteBasedTemplates()
    {
        return [
            //注册账号
            'SMS_58285247' => [
                'message' => '您正在进行注册账户操作，请于五分钟内在页面输入验证码${captcha}。注册成功后，即可直接使用您的注册码进行登录。',
                'params' => ['captcha'],
            ],
            //找回密码
            'SMS_58200292' => [
                'message' => '您正在进行密码找回操作，请在页面输入验证码${captcha}，五分钟内有效，请勿告知他人。',
                'params' => ['captcha'],
            ],
            //绑定手机
            'SMS_58175300' => [
                'message' => '您正在进行绑定手机操作，请在页面输入验证码${captcha}，五分钟内有效，请勿告知他人。',
                'params' => ['captcha'],
            ],
            //改绑手机
            'SMS_58200293' => [
                'message' => '您正在进行改绑手机操作，请在页面输入验证码${captcha}，五分钟内有效，请勿告知他人。',
                'params' => ['captcha'],
            ],
            //修改基本信息
            'SMS_59805070' => [
                'message' => '您正在进行基本信息修改操作，请在页面上输入验证码${captcha}，五分钟内有效，请勿告知他人。',
                'params' => ['captcha'],
            ],
            //给上级督导发短信
            'SMS_61725380'=>[
                'message'=>'你好，[${saleName}]督导，门店：[${shopName}]，id：[${account}]，电话：[${tel}]，正在进行质保系统操作，需要与你确认，在核实情况后可以将此验证码[${captcha}]发送给门店',
                'params'=>['saleName','shopName','account','tel','captcha'],
            ],
            //质保单查询
            'SMS_95490059' => [
                'message' => '您正在进行质保单查询操作，请在页面输入验证码${captcha}，五分钟内有效，请勿告知他人。',
                'params' => ['captcha'],
            ],
        ];
    }

    /**
     * 获取短信发送间隔时间，单位：秒
     * 若不需要规定间隔时间，则返回0或false
     *
     * @return integer
     */
    public static function getSendIntervalSecond()
    {
        return 0;
    }

    /**
     * 短信发送请求提交之后的额外操作
     * 执行该操作时无法保证短信发送成功，根据$sendResult参数获取发送状态
     *
     * @param array $sendResult 发送状态
     * 发送成功：```$sendResult = ['success' => true]```
     * 发送失败：```$sendResult = ['success' => false, 'message' => 'error message']```
     * @param mix $return 错误回调
     * @return mix 不检查该方法的回调，但会拦截该方法的抛错
     */
    public function doAfterSend($sendResult, $return = 'throw')
    {
        return $sendResult;
    }

    /**
     * 获取指定保存验证码的数据表AR
     *
     * @return ActiveRecord
     */
    protected static function getActiveRecord(){
        return new CustomSmsAR();
    }

    /**
     * 获取数据表中手机的字段名
     *
     * @return string
     */
     protected static function getMobileField(){
         return "mobile";

     }

    /**
     * 获取数据表中验证码的字段名
     *
     * @return string
     */
     protected static function getCaptchaField(){
         return 'captcha';

     }

    /**
     * 获取短信模板参数中短信的参数名
     *
     * @return string
     */
     protected static function getCaptchaParamName(){
       return 'captcha';
     }

    /**
     * 获取数据表中短信发送时间的字段名
     *
     * @return string
     */
     protected static function getSendDatetimeField(){
        return 'send_date_time';
     }


    /**
     * 获取数据表中短信发送时间戳的字段名
     *
     * @return string
     */
     protected static function getSendUnixtimeField(){
         return 'send_unix_time';
     }

     protected static function getStatusField(){
        return 'status';
     }

     protected static function getPrimaryField(){
        return 'id';
     }



    /**
     * 获取数据表需要储存的额外数据
     *
     * @return array
     *
     * ```
     * return [
     *     'extra_fieldname_1' => 'fieldvalue_1',
     *     'extra_fieldname_2' => 'fieldvalue_2',
     *     ...
     * ];
     * ```
     */
     protected static function getExtraSaveData(){
         return [];
     }

    /**
     * 获取验证码失效时间，单位：秒
     *
     * @return integer
     */
     public static function getExpireSecond(){
         return 300;
     }



}
