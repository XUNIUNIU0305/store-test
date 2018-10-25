<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 15:05
 */

namespace custom\models\parts\sms;


use common\models\parts\sms\SmsAbstract;

class Sms extends SmsAbstract
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
            //质保单数据导入
            'SMS_135801204' => [
                'message' => '您的车膜质保单已生成！如需查询，请戳：http://t.cn/R1PDOGd。',
                'params' => [],
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
        return [];
    }


}