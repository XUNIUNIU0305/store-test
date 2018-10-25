<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;
use yii\base\InvalidCallException;
use common\models\parts\trade\recharge\nanjing\message\ExecuteLog;

abstract class Base{

    private $_allRequestAttrs = [
        'AcctType', //(绑定)账号类型
        'AcctName', //绑定账号名称
        'AcctNo', //绑定账号
        'BranchId', //开户行行号
        'BeginDate', //开始时间
        'CifType', //客户类型
        'CifName', //开户户名
        'CheckAmount', //校验金额
        'Currency', //币种
        'Direction', //冻结方向
        'EndDate', //结束日期
        'FeeAmount', //手续费金额
        'IsRate', //是否计息
        'IsBackFee', //是否退手续费 默认1，退手续费
        'IdType', //证件类型
        'IdNo', //证件号码
        'Limit', //查询数据记录数 默认50
        'MerchantId', //接入方商户编号
        'MerUserId', //商户平台会员号|客户编号
        'MerUserAcctNo', //入金子账户|付款子账号|买方交易账号
        'MerchantSeqNo', //交易流水号
        'MerchantDateTime', //交易时间
        'MerSellerId', //卖方商户会员号
        'MerSellerAcctNo', //卖方交易账号
        'MobilePhone', //银行预留电话号码
        'OperType', //操作类型
        'OrderNo', //订单编号
        'OrgMerchantSeqNo', //原交易流水号
        'ProductInfo', //商品信息
        'PayDevide', //付款期次
        'Remark1', //备注1
        'Remark2', //备注2
        'RcvAcctNo', //收款账户
        'RcvAcctName', //收款账户行名
        'RcvBankId', //收款账户行号
        'Record', //起始记录数，默认0
        'TransAmount', //交易金额
        'TransAmount1', //交易金额
        'TransAmount2', //交易金额
        'MsgType', //短信类型
        'VerCode', //短信验证码
        'VerSeqNo', //标志位|验证流水
        'VirAcctNo', //账户账号
        'WithdrawType', //出金类型
    ];

    protected $merchantCert; //商户数字证书文件路径
    protected $merchantCertPassword; //商户数字证书密码
    protected $gateway; //通信网关
    protected $paygateCert; //银行支付平台证书文件

    protected $plain;

    const OPERATION_ACCOUNT = 'TP01'; //二级账户开立（开户、修改、注销）
    const OPERATION_ACTIVATION = 'TP03'; //二级账户激活
    const OPERATION_DEPOSIT = 'TP04'; //入金
    const OPERATION_DRAW = 'TP05'; //出金
    const OPERATION_FREEZE = 'TP06'; //资金冻结（担保交易功能）
    const OPERATION_THAW = 'TP07'; //资金解冻（担保交易功能）
    const OPERATION_PAYMENT = 'TP08'; //资金支付
    const OPERATION_REFUND = 'TP09'; //资金退货
    const OPERATION_GATEWAY_PAYMENT = 'TP20'; //网关支付
    const OPERATION_CONFIRM_AND_CANCEL_PAYMENT = 'TP30'; //确认/撤销支付（基于担保支付）
    const OPERATION_QUERY_CUSTOMER_INFO = 'QR04'; //签约客户信息查询
    const OPERATION_QUERY_BALANCE = 'QR06'; //余额查询
    const OPERATION_QUERY_DEPOSIT_AND_DEFRAYAL_STATEMENT = 'QR02'; //出入金明细查询
    const OPERATION_QUERY_FREEZE_AND_THAW = 'QR01'; //冻结、解冻明细查询
    const OPERATION_QUERY_PAYMENT_AND_REFUND_STATEMENT = 'QR03'; //交易明细查询
    const OPERATION_QUERY_ACCOUNT_STATEMENT = 'QR05'; //二级账户明细查询
    const OPERATION_CAPTCHA = 'TP10'; //获取短信验证码

    /**
     * 获取接口参数
     *
     * 键名类型String，参数名
     * 键值类型Boolean，是否必须
     *
     * @return array
     */
    abstract public function getAttrs() : array;

    /**
     * 获取当前操作
     */
    abstract public function getOperation() : string;

    /**
     * 设置参数，并验证参数合法性
     */
    final public function __set($name, $value){
        if(isset($this->plain[$name])){
            if(call_user_func([$this, 'verify' . $name], $value)){
                $this->plain[$name] = $value;
            }else{
                throw new InvalidCallException("param: {$name} is set an unavailable value");
            }
        }else{
            throw new InvalidCallException('unavailable param: ' . $name);
        }
    }

    /**
     * 获取参数值
     *
     * @return mix
     */
    final public function __get($name){
        if(isset($this->plain[$name])){
            return (is_bool($value = $this->plain[$name]) ? null : $value);
        }else{
            throw new InvalidCallException('unavailable param: ' . $name);
        }
    }

    /**
     * 初始化对象
     */
    final public function __construct(array $config){
        foreach(['merchantCert', 'merchantCertPassword', 'gateway', 'paygateCert'] as $param){
            $this->{$param} = $config[$param];
            unset($config[$param]);
        }
        //$this->merchantCertPassword = Yii::$app->params['NANJING_Merchant_Password'];
        //$this->gateway = Yii::$app->params['NANJING_Gateway'];
        //$this->merchantCert = __DIR__ . '/cert/merchant.pfx';
        //$this->paygateCert = __DIR__ . '/cert/cert.cer';
        $this->plain = $this->getAttrs();
        foreach($this->plain as $attrName => $boolean){
            if(!in_array($attrName, $this->_allRequestAttrs))throw new InvalidConfigException('unavailable param: ' . $attrName);
            if(!is_bool($boolean))throw new InvalidConfigException('unavailable value been set which param is: ' . $attrName);
        }
        foreach($config as $paramName => $paramValue){
            $this->$paramName = $paramValue;
        }
    }

    public function execute(){
        try{
            $plain = $this->getFilteredPlain();
            $packet = $this->generatePacket();
            $callback = $this->post($packet, $this->gateway);
            $executeLog = new ExecuteLog([
                'callbackPlain' => true,
                'originalPlain' => true,
                'operationType' => static::getOperation(),
                'merchantid' => $plain['MerchantId'] ?? 0,
                'meruserid' => $plain['MerUserId'] ?? '',
                'merchantSeqNo' => $plain['MerchantSeqNo'] ?? '',
                'requestData' => serialize($packet),
                'responseOriginalData' => $callback,
                'requestDatetime' => date('Y-m-d H:i:s', $time = time()),
                'requestUnixtime' => $time,
            ]);
            return new NanjingCallback($executeLog, $callback, $this->paygateCert);
        }catch(\Exception $e){
            return false;
        }
    }

    public function post(array $data, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 58);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cert/cacert.pem');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if($result){
            curl_close($ch);
            return $result;
        }else{
            $errMsg = curl_error($ch);
            $errNo = curl_errno($ch);
            curl_close($ch);
            throw new \Exception("Curl Failed ({$errNo}): {$errMsg}");
        }
    }

    /**
     * 签名
     *
     * @return string
     */
    public function sign(string $plain){
        $merchantCertData = [];
        $pkcs12 = file_get_contents($this->merchantCert);
        openssl_pkcs12_read($pkcs12, $merchantCertData, $this->merchantCertPassword);
        $pkeyid = openssl_pkey_get_private($merchantCertData['pkey']);
        openssl_sign($plain, $signature, $pkeyid, OPENSSL_ALGO_MD5);
        openssl_free_key($pkeyid);
        return bin2hex($signature);
    }

    protected function getFilteredPlain(){
        return array_filter($this->plain, function($value, $key){
            if(is_bool($value)){
                if($value === true){
                    throw new InvalidCallException('The param missed: ' . $key);
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 生成Plain字符串
     */
    public function generatePlainString(){
        $plain = $this->getFilteredPlain();
        $params = [];
        foreach($plain as $param => $value){
            $params[] = $param . '=' . urlencode($value);
        }
        return implode('|', $params) . '|';
    }

    /**
     * 生成请求参数
     */
    public function generatePacket(){
        return [
            'transName' => $this->getOperation(),
            'Plain' => ($plain = $this->generatePlainString()),
            'Signature' => $this->sign($plain),
        ];
    }

    public function verifyAcctType($value){
        if($this->getOperation() == self::OPERATION_GATEWAY_PAYMENT){
            return in_array($value, [
                11, //个人卡
                12, //企业账号
            ]);
        }else{
            return in_array($value, [
                0, //企业账号
                1, //卡
            ]);
        }
    }

    public function verifyAcctName($value){
        return (is_string($value) && $value !== '');
    }

    public function verifyAcctNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyBranchId($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyBeginDate($value){
        if(strlen($value) != 8)return false;
        if(!$unixTime = strtotime($value))return false;
        return ($value == date('Ymd', $unixTime));
    }

    public function verifyCifType($value){
        return in_array($value, [
            0, //个人
            1, //企业
        ]);
    }

    public function verifyCifName($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyCheckAmount($value){
        return $this->verifyAmount($value, false);
    }

    public function verifyCurrency($value){
        return in_array($value, [
            156, //人民币 （默认）
            826, //英镑
            344, //香港元
            840, //美元
            756, //瑞士法郎
            392, //日元
            276, //德国马克
            250, //法国法郎
            360, //卢比
            408, //北朝鲜圆
        ]);
    }

    public function verifyDirection($value){
        return in_array($value, [
            1, //冻结买方
            2, //冻结卖方
            3, //双向冻结
        ]);
    }

    public function verifyEndDate($value){
        return $this->verifyBeginDate($value);
    }

    public function verifyFeeAmount($value){
        return $this->verifyAmount($value, true);
    }

    public function verifyIsRate($value){
        return in_array($value, [
            0, //不计息
            1, //计息
        ]);
    }

    public function verifyIsBackFee($value){
        return in_array($value, [
            0, //不退手续费
            1, //退手续费
        ]);
    }

    public function verifyIdType($value){
        return in_array($value, [
            1, //二代居民身份证
            2, //户口本
            3, //护照
            4, //港澳居民来往内陆通行证
            5, //港澳同胞回乡证
            6, //台湾居民来往大陆通行证
            7, //其他有效证件
            'm', //机构代码证
            'n', //营业执照
            'p', //登记证书
            'q', //国税税务登记证号
            'r', //地税税务登记证号
        ]);
    }

    public function verifyIdNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyLimit($value){
        return $this->verifyPositiveInteger($value);
    }

    public function verifyMerchantId($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyMerUserId($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyMerUserAcctNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyMerchantSeqNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyMerchantDateTime($value){
        if($unixTime = strtotime($value)){
            return ($value == date('YmdHis', $unixTime));
        }else{
            return false;
        }
    }

    public function verifyMerSellerId($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyMerSellerAcctNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyMobilePhone($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyOperType($value){
        switch($this->getOperation()){
            case self::OPERATION_ACCOUNT:
                return in_array($value, [
                    1, //开户
                    2, //修改
                    3, //注销
                    4, //开立子账户
                ]);

            case self::OPERATION_PAYMENT:
                return in_array($value, [
                    101, //余额支付
                    102, //银行卡支付
                    201, //担保余额支付
                    202, //担保银行卡支付
                    300, //强制支付
                ]);

            case self::OPERATION_GATEWAY_PAYMENT:
                return in_array($value, [
                    102, //直接支付
                    202, //担保支付
                    300, //入金
                ]);

            case self::OPERATION_CONFIRM_AND_CANCEL_PAYMENT:
                return in_array($value, [
                    1, //确认支付
                    2, //撤销支付
                ]);

            case self::OPERATION_DRAW:
                return in_array($value, [
                    0, //出金到绑定账户（默认）
                    1, //出金到指定账户，平台配置允许出金到指定账户才会生效
                ]);

            default:
                return false;
        }
    }

    public function verifyOrderNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyOrgMerchantSeqNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyProductInfo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyPayDevide($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyRemark1($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyRemark2($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyRcvAcctNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyRcvAcctName($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyRcvBankId($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyRecord($value){
        return $this->verifyPositiveInteger($value, true);
    }

    public function verifyTransAmount($value){
        return $this->verifyAmount($value, false);
    }

    public function verifyTransAmount1($value){
        return $this->verifyTransAmount($value);
    }

    public function verifyTransAmount2($value){
        return $this->verifyTransAmount($value);
    }

    public function verifyMsgType($value){
        return in_array($value, [
            1, //开户
            2, //账户绑定
            3, //入金
            4, //出金
            5, //支付
        ]);
    }

    public function verifyVerCode($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyVerSeqNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyVirAcctNo($value){
        return $this->verifyNotEmpty($value);
    }

    public function verifyWithdrawType($value){
        return ((string)$value == '01');
    }

    /**
     * 验证非空
     */
    protected function verifyNotEmpty($value){
        return !empty($value);
    }

    /**
     * 验证金额
     * @param mix $value 金额
     * @param bool $canBeZero 可否可为零
     */
    protected function verifyAmount($value, bool $canBeZero){
        if(!is_numeric($value))return false;
        if($canBeZero){
            return ($value >= 0);
        }else{
            return ($value > 0);
        }
    }

    /**
     * 验证正整数
     * @param mix $value 字符
     * @param bool $canBeZero 是否能为ling
     */
    protected function verifyPositiveInteger($value, bool $canBeZero = false){
        if(!is_numeric($value))return false;
        $numericTypeData = $value + 0;
        if(!is_int($numericTypeData))return false;
        return ($canBeZero ? ($numericTypeData >= 0) : ($numericTypeData > 0));
    }
}
