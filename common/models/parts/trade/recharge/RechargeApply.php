<?php
namespace common\models\parts\trade\recharge;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use common\ActiveRecord\RechargeApplyAR;
use common\models\parts\trade\RechargeMethodAbstract;
use common\models\parts\trade\recharge\alipay\Alipay;
use common\models\parts\trade\recharge\wechat\Wechat;
use common\models\parts\trade\recharge\nanjing\NanjingGatewayPayment;
use common\models\parts\trade\recharge\abc\AbchinaGatewayPayment;

class RechargeApply extends Object{

    const STATUS_WAIT = 0;
    const STATUS_SUCCESS = 1;

    const USER_TYPE_CUSTOMER = 1;
    const USER_TYPE_SUPPLIER = 2;
    const USER_TYPE_ADMINISTRATOR = 3;

    //recharge_apply表主键
    public $id;
    //充值申请号
    public $rechargeNumber;

    protected $detail;
    protected $AR;

    public function init(){
        if($this->rechargeNumber && is_null($this->id)){
            if($AR = RechargeApplyAR::findOne(['recharge_number' => $this->rechargeNumber])){
                $this->id = $AR->id;
            }
        }
        if(!$this->id ||
            (!$this->AR = RechargeApplyAR::findOne($this->id)) ||
            (!$this->detail = $this->getUserApply($this->userType, $this->userApplyId))
        )throw new InvalidCallException;
    }

    /**
     * 获取充值申请号
     *
     * @return integer
     */
    public function getRechargeNo(){
        return $this->AR->recharge_number;
    }

    /**
     * 获取具体充值申请对象
     *
     * @return Object
     */
    public function getDetail(){
        return $this->detail;
    }

    /**
     * 获取用户类型；CUSTOM SUPPLY
     *
     * @return integer
     */
    public function getUserType(){
        return $this->AR->apply_user_type;
    }

    /**
     * 获取用户申请ID
     *
     * @return integer
     */
    public function getUserApplyId(){
        return $this->AR->corresponding_recharge_apply_id;
    }

    /**
     * 获取充值状态
     *
     * @return integer
     */
    public function getStatus(){
        return $this->AR->status;
    }

    /**
     * 获取是否未充值
     *
     * @return boolean
     */
    public function getIsWait(){
        return $this->status == self::STATUS_WAIT;
    }

    /**
     * 设置当前充值申请为已充值
     *
     * @return integer|false
     */
    public function setRecharged(){
        if($this->status == self::STATUS_WAIT){
            $this->AR->status = self::STATUS_SUCCESS;
            return $this->AR->update();
        }else{
            return false;
        }
    }

    /**
     * 获取充值时间
     *
     * @return string
     */
    public function getApplyTime($unixTime = false){
        return $unixTime ? $this->AR->apply_unixtime : $this->AR->apply_datetime;
    }

    /**
     * 生成充值路径
     *
     * @return string
     */
    public function generateRechargeUrl(array $config = null){
        $rechargeObj = $this->rechargeObj;
        return $rechargeObj->generatePayUrl($config);
    }

    /**
     * 获取具体申请对象
     *
     * @return Object
     */
    public function getRechargeObj(){
        $rechargeMethod = $this->detail->rechargeMethod;
        $classes = $this->getRechargeObjClass();
        if(!$rechargeClass = $classes[$rechargeMethod] ?? null)return false;
        $reflectionClass = new \ReflectionClass($rechargeClass['class']);
        return $reflectionClass->newInstance(['config' => $rechargeClass['config']]);
    }

    /**
     * 获取全部充值状态
     *
     * @return array
     */
    public static function getStatuses(){
        return [
            self::STATUS_WAIT,
            self::STATUS_SUCCESS,
        ];
    }

    /**
     * 获取全部用户类型
     *
     * @return array
     */
    public static function getUserTypes(){
        return [
            self::USER_TYPE_CUSTOMER,
            self::USER_TYPE_SUPPLIER,
            self::USER_TYPE_ADMINISTRATOR,
        ];
    }

    /**
     * 获取全部充值方式对象
     *
     * @return array
     */
    public function getRechargeObjClass(){
        return [
            RechargeMethodAbstract::METHOD_ALIPAY => [
                'class' => Alipay::className(),
                'config' => [
                    'out_trade_no' => $this->rechargeNo,
                    'subject' => '充值： RMB ' . $this->detail->rechargeAmount,
                    'total_fee' => $this->detail->rechargeAmount,
                    'return_url' => $this->detail->returnUrl,
                    'notify_url' => Alipay::getNotifyUrl(),
                ],
            ],
            RechargeMethodAbstract::METHOD_WX_INWECHAT => [
                'class' => Wechat::className(),
                'config' => [
                    'out_trade_no' => $this->rechargeNo,
                    'body' => '充值： RMB ' . $this->detail->rechargeAmount,
                    'total_fee' => $this->detail->rechargeAmount * 100,
                    'trade_type' => 'JSAPI',
                    'user_type' => $this->userType,
                    'pay_url' => '/wxpay/index',
                ],
            ],
            RechargeMethodAbstract::METHOD_GATEWAY_PERSON => [
                'class' => NanjingGatewayPayment::className(),
                'config' => [
                    'merchantSeqNo' => $this->rechargeNo,
                    'acctType' => '11',
                    'transAmount' => $this->detail->rechargeAmount,
                    'rechargeNo' => $this->rechargeNo,
                    'userId' => $this->detail->userId,
                    'userType' => $this->userType,
                ],
            ],
            RechargeMethodAbstract::METHOD_GATEWAY_CORP => [
                'class' => NanjingGatewayPayment::className(),
                'config' => [
                    'merchantSeqNo' => $this->rechargeNo,
                    'acctType' => '12',
                    'transAmount' => $this->detail->rechargeAmount,
                    'rechargeNo' => $this->rechargeNo,
                    'userId' => $this->detail->userId,
                    'userType' => $this->userType,
                ],
            ],
            RechargeMethodAbstract::METHOD_ABCHINA_GATEWAY => [
                'class' => AbchinaGatewayPayment::className(),
                'config' => [
                    'rechargeId' => $this->id,
                    'billNo' => $this->rechargeNo,
                    'account' => $this->detail->userAccount,
                    'accountType' => $this->userType,
                    'totalAmount' => $this->detail->rechargeAmount,
                    'productData' => '',
                    'settlementAmount' => $this->detail->rechargeAmount,
                    'loginName' => '',
                ],
            ],
        ];
    }

    /**
     * 获取用户具体充值申请对象
     *
     * @return Object
     */
    protected function getUserApply(int $userType, int $applyId){
        switch($userType){
            case self::USER_TYPE_CUSTOMER:
                return new CustomerApply(['id' => $applyId]);

            case self::USER_TYPE_ADMINISTRATOR:
                return new AdministratorApply(['id' => $applyId]);

            default:
                return false;
        }
    }
}
