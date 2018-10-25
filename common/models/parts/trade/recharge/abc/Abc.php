<?php
namespace common\models\parts\trade\recharge\abc;

use Yii;
use Java;
use yii\base\Object;
use common\ActiveRecord\AbchinaNotifyLogAR;

class Abc extends Object{

    const TOKEN_EXPIRE_TIME = 7000;
    const TOKEN_KEY = 'abc_token';

    private static $_included;
    private static $_client;

    public function init(){
        try{
            $localCookie = $_COOKIE[Yii::$app->session->name] ?? '';
            $_COOKIE = [
                Yii::$app->session->name => $localCookie,
            ];
        }catch(\Exception $e){}
        if(!static::$_included){
            include(Yii::$app->params['ABCHINA_Java_Bridge_Url']);
            static::$_client = new Java('com.abc.DefaultAbchinaClient');
            static::$_included = true;
        }
    }

    public function addDealer(string $dealerNo, string $dealerName, string $contact, string $contactTel){
        $dealerAddRequest = new Java('com.abc.request.AbchinaApiDealerAddRequest');
        $dealerAddRequest->setDealerno($dealerNo);
        $dealerAddRequest->setDealername($dealerName);
        $dealerAddRequest->setContact($contact);
        $dealerAddRequest->setContacttel($contactTel);
        return $this->executeAction($dealerAddRequest);
    }

    public function getToken(){
        if($token = Yii::$app->cache->get(self::TOKEN_KEY)){
            return $token;
        }else{
            try{
                if($token = $this->achieveToken()){
                    if(Yii::$app->cache->set(self::TOKEN_KEY, $token, self::TOKEN_EXPIRE_TIME)){
                        return $token;
                    }else{
                        throw new \Exception;
                    }
                }else{
                    throw new \Exception;
                }
            }catch(\Exception $e){
                return false;
            }
        }
    }

    /**
     * @return array|boolean false
     * [
     *     'OrgId' => `enum`, //请求方企业ID
     *     'DealerOrgId' => `string`, //经销商企业号
     *     'DealerNum' => `string`, //外部经销商编号
     *     'DealerName' => `string`, //经销商名称
     *     'DealerRemarkName' => `string`, //经销商备注名
     *     'AuditState' => `enum`, //审核状态
     *     'Address' => `string`, //地址
     *     'Contact' => `string`, //联系人
     *     'ContactPhone' => `string`, //联系人电话
     *     'AuditResult' => `enum`, //审核结果
     * ];
     */
    public function achieveDealerInfo($account){
        $dealerSingleQueryRequest = new Java('com.abc.request.AbchinaApiDealerSingleQueryRequest');
        $dealerSingleQueryRequest->setDealerNO($account);
        $result = $this->executeAction($dealerSingleQueryRequest);
        return isset($result['msg']['Value']['List'][0]) ? $result['msg']['Value']['List'][0] : false;
    }

    /**
     * @param array $data
     * [
     *     'BillNO' => `string`, //客户系统订单号
     *     'DealerNO' => `string`, //经销商编号
     *     'TotalAmount' => `decimal`, //订单金额
     *     'ProductData' => `string`, //Json格式列表
     *         'NO' => `string`, //商品编号
     *         'Name' => `string`, //商品名称
     *         'Quantity' => `decimal`, //商品数量
     *         'Price' => `decimal`, //商品单价
     *         'TotalAmount' => `decimal`, //商品总价
     *         'UomName' => `string`, //商品单位
     *     'SettlementAmount' => `decimal`, //订单成交金额
     *     'LoginName' => `string`, //登录名称
     * ];
     */
    public function addOrder(array $data){
        $BillNO = null;
        $DealerNO = null;
        $TotalAmount = null;
        $ProductData = null;
        $SettlementAmount = null;
        $LoginName = null;
        extract($data, EXTR_IF_EXISTS);
        $orderAddRequest = new Java('com.abc.request.AbchinaApiOrderAddRequest');
        foreach([
            'BillNO' => 'setBillno',
            'DealerNO' => 'setDealerno',
            'TotalAmount' => 'setTotalamount',
            'ProductData' => 'setProductdata',
            'SettlementAmount' => 'setSettlementamount',
            'LoginName' => 'setLoginName',
        ] as $variable => $function){
            if(!is_null(${$variable})){
                if($variable == 'TotalAmount' || $variable == 'SettlementAmount'){
                    ${$variable} = new Java('java.math.BigDecimal', (string)${$variable});
                }
                $orderAddRequest->$function(${$variable});
            }
        }
        $result = $this->executeAction($orderAddRequest);
        return isset($result['msg']['Value']['BillId']) ? $result['msg']['Value']['BillId'] : false;
    }

    public function achieveJwt(string $loginName = null, string $password = null){
        $jwtGetRequest = new Java('com.abc.request.AbchinaApiJwtGetRequest');
        if(!is_null($loginName)){
            $jwtGetRequest->setLoginName($loginName . 'admin');
        }
        if(!is_null($password)){
            $jwtGetRequest->setPassword($password);
        }
        $result = $this->executeAction($jwtGetRequest);
        return isset($result['msg']['Value']['jwt']) ? $result['msg']['Value']['jwt'] : false;
    }

    public function generatePayUrl(string $dealerNo, string $orderId, string $redirectUrl){
        $redirectUrl = urlencode($redirectUrl);
        if(!$jwt = $this->achieveJwt($dealerNo, '123456'))return false;
        return Yii::$app->params['ABCHINA_Pay_Url'] . "?Ids=[\"{$orderId}\"]&BillPurpose=1&jwt=" . $jwt . '&RedirectUrl=' . $redirectUrl;
    }

    public function verifyNotify(string $notifyMsg){
        try{
            return java_values(self::$_client->executeVerifyResponseWithCert($notifyMsg));
        }catch(\Exception $e){
            return false;
        }
    }

    public function decodeNotify(string $notifyMsg, string $encryptType, string $charset){
        try{
            return java_values(self::$_client->executeDecodeReceiveMsg($notifyMsg, $encryptType, $charset));
        }catch(\Exception $e){
            return false;
        }
    }

    public function writeNotifyLog(string $notifyMsg){
        preg_match('/<ExtOrderID>(.*?)<\/ExtOrderID>/', $notifyMsg, $extOrderIds);
        $rechargeNumber = $extOrderIds[1] ?? false;
        preg_match('/<EbizBillNO>(.*?)<\/EbizBillNO>/', $notifyMsg, $ebizBillNos);
        $ebizBillNo = $ebizBillNos[1] ?? false;
        if(!$rechargeNumber || !$ebizBillNo)return false;
        return Yii::$app->RQ->AR(new AbchinaNotifyLogAR)->insert([
            'recharge_number' => $rechargeNumber,
            'ebiz_bill_no' => $ebizBillNo,
            'notify_msg' => $notifyMsg,
        ]);
    }

    private function executeAction($action){
        $result = static::$_client->excute($action, $this->getToken());
        return java_values($result);
    }

    private function achieveToken(){
        $token = static::$_client->executeAchieveToken();
        $accessToken = $token->getAppAccessToken();
        return java_values($accessToken);
    }
}
