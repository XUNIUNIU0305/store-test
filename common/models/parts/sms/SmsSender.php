<?php
namespace common\models\parts\sms;

use Yii;
use Curl\Curl;
use yii\base\Object;
use common\ActiveRecord\AliyunSmsLogAR;

class SmsSender extends Object{

    /**
     * 是否要保存短信发送记录
     */
    public $record = true;
    /**
     * 是否要验证手机号码格式
     */
    public $validateMobile = true;

    protected $host = 'http://dysmsapi.aliyuncs.com';
    protected $regionId = 'cn-hangzhou';
    protected $action = 'SendSms';
    protected $version = '2017-05-25';
    protected $signatureVersion = '1.0';
    protected $signatureMethod = 'HMAC-SHA1';

    private $_accessKeyId;
    private $_accessKeySecret;

    private $_nonce;
    private $_timestamp;

    public function init(){
        $this->_accessKeyId = Yii::$app->params['DYSMS_AccessKeyId'];
        $this->_accessKeySecret = Yii::$app->params['DYSMS_AccessKeySecret'];
    }

    /**
     * 发送短信
     *
     * @param SmsAbstract $sms 短信主体
     * @param mix $return 错误回调
     *
     * @return mix
     *
     * 发送的完整过程：
     * 验证手机号码格式
     * 验证短信发送间隔
     * 发送短信
     * 执行发送后操作
     * 保存发送记录
     * 保存验证码(若是短信验证码)
     *
     * 除了【发送短信】步骤外，其他均为非必须步骤
     */
    public function send(SmsAbstract $sms, $return = 'throw'){
        if($this->validateMobile){
            if(!$this->validateMobile($sms->mobiles))return Yii::$app->EC->callback($return, 'invalid mobile number');
        }
        if($interval = (int)$sms::getSendIntervalSecond()){
            if(!$this->validateInterval($sms, $interval))return Yii::$app->EC->callback($return, 'send interval too short');
        }
        if($sendResult = $this->sendMessage($sms, false)){
            try{
                $sms->doAfterSend($sendResult, 'throw');
            }catch(\Exception $e){
                return Yii::$app->EC->callback($return, 'Call Sms::afterSend() failed');
            }
            if($sendResult['Code'] != 'OK'){
                return Yii::$app->EC->callback($return, $sendResult['Message'] ?? 'unknown error');
            }
        }else{
            return Yii::$app->EC->callback($return, 'send sms failed');
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($this->record){
                $this->record($sms);
            }
            if($sms instanceof SmsCaptchaAbstract){
                $sms->setCanceled();
                $sms->save();
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'mysql');
        }
    }

    /**
     * 验证发送间隔
     * 如果未开启短信记录则无法验证
     * 如果有一个手机号码在间隔期内则所有号码均不发送短信
     * 发送手机过多时执行缓慢
     *
     * @param SmsAbstract $sms 短信主体
     * @param integer $interval 间隔时间
     *
     * @return boolean
     */
    protected function validateInterval(SmsAbstract $sms, int $interval){
        foreach($sms->mobiles as $mobile){
            $sendTime = Yii::$app->RQ->AR(new AliyunSmsLogAR)->scalar([
                'select' => ['send_unixtime'],
                'where' => [
                    'mobile' => $mobile,
                ],
                'orderBy' => [
                    'send_unixtime' => SORT_DESC,
                ],
            ]);
            if($sendTime && ($sendTime + $interval > Yii::$app->time->unixTime))return false;
        }
        return true;
    }

    /**
     * 记录短信发送结果
     * 批量发送过多手机时建议关闭
     *
     * @param SmsAbstract $sms 短信主体
     * @param mix $return 错误回调
     *
     * @return mix
     */
    protected function record(SmsAbstract $sms, $return = 'throw'){
        $message = $sms->getTemplateMessage(true);
        $sendDatetime = Yii::$app->time->fullDate;
        $sendUnixtime = Yii::$app->time->unixTime;
        $insertData = [];
        foreach($sms->mobiles as $mobile){
            $insertData[] = [$mobile, $message, $sendDatetime, $sendUnixtime];
        }
        try{
            $queryResult = Yii::$app->db->createCommand()->batchInsert(
                AliyunSmsLogAR::tableName(),
                ['mobile', 'message', 'send_datetime', 'send_unixtime'],
                $insertData
            )->execute();
        }catch(\Exception $e){
            $queryResult = false;
        }
        return $queryResult === false ? Yii::$app->EC->callback($return, 'mysql') : $queryResult;
    }

    /**
     * 发送短信
     *
     * @param SmsAbstract $sms 短信主体
     * @param mix $return 错误回调
     *
     * @return array|mix
     */
    protected function sendMessage(SmsAbstract $sms, $return = 'throw'){
        $curl = new Curl;
        $curl->setOpt(CURLOPT_FAILONERROR, false);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->get($this->host, $this->generateParams($sms));
        if($curl->error){
            return Yii::$app->EC->callback($return, 'send message failed');
        }else{
            return (array)$curl->response;
        }
    }

    protected function getNonce(){
        if(is_null($this->_nonce)){
            $this->_nonce = uniqid(mt_rand(0, 0xffff), true);
        }
        return $this->_nonce;
    }

    protected function getTimestamp(){
        if(is_null($this->_timestamp)){
            $this->_timestamp = gmdate('Y-m-d\TH:i:s\Z');
        }
        return $this->_timestamp;
    }

    /**
     * 生成发送参数
     *
     * @param SmsAbstract $sms 短信主体
     *
     * @return array
     */
    protected function generateParams(SmsAbstract $sms){
        $unsignedParams = [
            //'Signature' => '',
            'AccessKeyId' => $this->_accessKeyId,
            'Action' => $this->action,
            'RegionId' => $this->regionId,
            'SignatureMethod' => $this->signatureMethod,
            'SignatureNonce' => $this->nonce,
            'SignatureVersion' => $this->signatureVersion,
            'Timestamp' => $this->timestamp,
            'Version' => $this->version,
            'TemplateParam' => json_encode($sms->params),
            'PhoneNumbers' => implode(',', $sms->mobiles),
            'SignName' => $sms->signName,
            'TemplateCode' => $sms->template,
        ];
        if(!$sms->params){
            unset($unsignedParams['TemplateParam']);
        }
        return array_merge($unsignedParams, ['Signature' => $this->signString($unsignedParams)]);
    }

    protected function signString(array $unsignedParams){
        ksort($unsignedParams);
        $queryString = '';
        foreach($unsignedParams as $k => $v){
            $queryString .= '&' . $this->encodeString($k) . '=' . $this->encodeString($v);
        }
        $stringToSign = 'GET&%2F&' . $this->encodeString(substr($queryString, 1));
        return base64_encode(hash_hmac('sha1', $stringToSign, $this->_accessKeySecret . '&', true));
    }

    protected function encodeString(string $string){
        return str_replace(['+', '*', '%7E'], ['%20', '%2A', '~'], urlencode($string));
    }

    /**
     * 验证手机格式
     *
     * @param array $mobiles 手机号码
     *
     * @return boolean
     */
    protected function validateMobile(array $mobiles){
        foreach($mobiles as $mobile){
            if($mobile < 10000000000 || $mobile > 19999999999)return false;
        }
        return true;
    }
}
