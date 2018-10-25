<?php
namespace common\models\parts\sms;

use Yii;
use yii\base\InvalidConfigException;

abstract class SmsCaptchaAbstract extends SmsAbstract{

    const STATUS_UNUSED = 0;
    const STATUS_USED = 1;
    const STATUS_CANCELED = 2;

    private $_mobile;

    /**
     * 获取指定保存验证码的数据表AR
     *
     * @return ActiveRecord
     */
    abstract protected static function getActiveRecord();

    /**
     * 获取数据表中手机的字段名
     * field type: bigint
     *
     * @return string
     */
    abstract protected static function getMobileField();

    /**
     * 获取数据表中验证码的字段名
     * field type: int / varchar / char
     *
     * @return string
     */
    abstract protected static function getCaptchaField();

    /**
     * 获取短信模板参数中短信的参数名
     *
     * @return string
     */
    abstract protected static function getCaptchaParamName();

    /**
     * 获取数据表中短信发送时间的字段名
     * field type: datetime
     *
     * @return string
     */
    abstract protected static function getSendDatetimeField();

    /**
     * 获取数据表中短信发送时间戳的字段名
     * field type: int
     *
     * @return string
     */
    abstract protected static function getSendUnixtimeField();

    /**
     * 获取数据表中短信状态的字段名
     * 状态：未使用、已使用、已废弃
     * field type: tinyint
     *
     * @return string
     */
    abstract protected static function getStatusField();

    /**
     * 获取数据表中的主键字段名
     * field type:int auto_increment
     *
     * @return string
     */
    abstract protected static function getPrimaryField();

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
    abstract protected static function getExtraSaveData();

    /**
     * 获取验证码失效时间，单位：秒
     *
     * @return integer
     */
    abstract public static function getExpireSecond();

    /**
     * 如果发送短信验证码，一次只能发送一个手机
     */
    public function init(){
        parent::init();
        if(count($this->mobiles) != 1)throw new InvalidConfigException('captcha can only send to one mobile phone');
        $this->_mobile = current($this->mobiles);
        if(!isset($this->params[static::getCaptchaParamName()]))throw new InvalidConfigException('invalid captcha param name');
    }

    public function setCanceled($return = 'throw'){
        return (static::getActiveRecord()::updateAll([
            static::getStatusField() => self::STATUS_CANCELED,
        ], [
            static::getMobileField() => $this->_mobile,
            static::getStatusField() => self::STATUS_UNUSED,
        ]) === false ? Yii::$app->EC->callback($return, 'mysql') : true);
    }

    /**
     * 短信发送成功后存入数据表
     *
     * @return mix
     */
    public function save($return = 'throw'){
        $captcha = $this->params[static::getCaptchaParamName()];
        $insertData = array_merge(static::getExtraSaveData(), [
            static::getMobileField() => $this->_mobile,
            static::getCaptchaField() => $captcha,
            static::getSendDatetimeField() => Yii::$app->time->fullDate,
            static::getSendUnixtimeField() => Yii::$app->time->unixTime,
            static::getStatusField() => self::STATUS_UNUSED,
        ]);
        return Yii::$app->RQ->AR(static::getActiveRecord())->insert($insertData, $return);
    }

    /**
     * 验证短信验证码是否正确
     *
     * @param integer $mobile 手机号码
     * @param mix $captcha 验证码
     * 
     * @return boolean
     */
    public static function validateCaptcha($mobile, $captcha){
        $rowData = Yii::$app->RQ->AR(static::getActiveRecord())->one([
            'select' => [
                static::getPrimaryField(),
                static::getCaptchaField(),
                static::getSendUnixtimeField(),
            ],
            'where' => [
                static::getMobileField() => $mobile,
                static::getStatusField() => self::STATUS_UNUSED,
            ],
            'orderBy' => [
                static::getSendUnixtimeField() => SORT_DESC,
            ],
            'limit' => 1,
        ]);
        if($rowData){
            if(($rowData[static::getCaptchaField()] == $captcha && Yii::$app->time->unixTime < ($rowData[static::getSendUnixtimeField()] + static::getExpireSecond()))){
                return Yii::$app->RQ->AR(static::getActiveRecord()::findOne($rowData[static::getPrimaryField()]))->update([
                    'status' => self::STATUS_USED,
                ], false) ? true : false;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
