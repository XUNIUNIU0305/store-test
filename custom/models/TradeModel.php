<?php
namespace custom\models;

use Yii;
use common\models\Model;
use common\models\parts\trade\recharge\alipay\Alipay;
use custom\models\parts\UrlParamCrypt;

class TradeModel extends Model{

    public static function verifyAlipayReturn(){
        return (new Alipay)->verifyReturn();
    }

    public static function getAlipayReturn(){
        if((new Alipay)->verifyReturn()){
            return [
                'tradeNo' => $_GET['out_trade_no'],
                'alipayNo' => $_GET['trade_no'],
                'totalFee' => $_GET['total_fee'],
            ];
        }else{
            return false;
        }
    }

    public static function getBalanceReturn(){
        if(isset($_GET['q']) && ($totalFee = (new UrlParamCrypt)->decrypt(str_replace(['+', ' '], ['%2B', '%2B'], $_GET['q'])))){
            return [
                'totalFee' => $totalFee,
            ];
        }else{
            return false;
        }
    }
}
