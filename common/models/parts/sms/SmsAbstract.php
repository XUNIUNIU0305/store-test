<?php
namespace common\models\parts\sms;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

abstract class SmsAbstract extends Object{

    /**
     * 手机号码
     * integer|array
     */
    public $mobile;
    /**
     * 短信签名
     * 必须在aliyun审核通过
     * string
     */
    public $signName;
    /**
     * 短信模板ID
     * 必须在aliyun审核通过
     * string
     */
    public $templateCode;
    /**
     * 短信模板内定义的参数
     * 完整参数列表由SmsAbstract::getTemplates()定义
     * array
     */
    public $param = [];

    private $_mobiles;
    private $_template;
    private $_signName;
    private $_params;

    /**
     * 获取基于站点的短信签名
     *
     * @return array
     */
    abstract protected static function getSiteBasedSignNames();

    /**
     * 获取基于站点的短信模板
     *
     * @return array
     */
    abstract protected static function getSiteBasedTemplates();

    /**
     * 获取短信发送间隔时间，单位：秒
     * 若不需要规定间隔时间，则返回0或false
     *
     * @return integer
     */
    abstract public static function getSendIntervalSecond();

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
    abstract public function doAfterSend($sendResult, $return = 'throw');

    public function init(){
        if(is_array($this->mobile)){
            $this->_mobiles = $this->mobile;
            $this->_mobiles = array_map(function($mobile){
                return (int)$mobile;
            }, $this->mobile);
        }else{
            $this->_mobiles = (array)((int)$this->mobile);
        }
        if(in_array($this->signName, self::getSignNames())){
            $this->_signName = $this->signName;
        }else{
            throw new InvalidConfigException('invalid sign name');
        }
        if(isset(self::getTemplates()[$this->templateCode])){
            $this->_template = $this->templateCode;
        }else{
            throw new InvalidConfigException('invalid template code');
        }
        if(is_array($this->param)){
            $params = self::getTemplates()[$this->_template]['params'];
            $outerParams = array_keys($this->param);
            if(count($params) != count($outerParams) || array_diff($params, $outerParams)){
                throw new InvalidConfigException('invalid params');
            }else{
                $this->_params = array_map(function($param){
                    return strval($param);
                }, $this->param);
            }
        }else{
            throw new InvalidConfigException('params must be array');
        }
    }

    /**
     * 获取手机号码列表
     *
     * @return array
     */
    public function getMobiles(){
        return $this->_mobiles;
    }

    /**
     * 获取短信签名
     *
     * @return string
     */
    public function getSignName(){
        return $this->_signName;
    }

    /**
     * 获取短信模板ID
     *
     * @return string
     */
    public function getTemplate(){
        return $this->_template;
    }

    /**
     * 获取短信模板文字
     *
     * @param boolean $convert 是否转换模板中的参数
     * @return string
     */
    public function getTemplateMessage($convert = false){
        $message = self::getTemplates()[$this->_template]['message'];
        if($convert){
            foreach($this->params as $paramName => $paramValue){
                $message = str_replace('${' . $paramName . '}', $paramValue, $message);
            }
        }
        return $message;
    }

    /**
     * 获取等短信模板的参数
     *
     * @return array
     */
    public function getParams(){
        return $this->_params;
    }

    /**
     * 获取短信签名列表
     *
     * @return array
     */
    public static function getSignNames(){
        return array_merge(static::getSiteBasedSignNames(), [
            '九大爷平台',
        ]);
    }

    /**
     * 获取短信模板列表，包括模板文字，模板参数
     *
     * @return array
     */
    public static function getTemplates(){
        return array_merge(static::getSiteBasedTemplates(), [
            'SMS_53810334' => [
                'message' => '您的短信验证码为${captcha}',
                'params' => ['captcha'],
            ],
            'SMS_94640099' => [
                'message' => '您正在进行账户提现操作，请于五分钟内在页面输入验证码：${captcha}',
                'params' => ['captcha'],
            ],
            'SMS_100060008' => [
                'message' => '您账户[${account}]的提现申请（${rmb}元）已成功通过审核，资金将在两小时内到账，请及时查验款项。',
                'params' => ['account', 'rmb'],
            ],
        ]);
    }
}
