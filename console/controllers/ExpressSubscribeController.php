<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/10
 * Time: 下午5:19
 */

namespace console\controllers;

use common\ActiveRecord\ExpressChangeLogAR;
use common\ActiveRecord\ExpressCorporationAR;
use common\ActiveRecord\OrderAR;
use common\models\parts\Order;
use console\controllers\basic\Controller;
use Curl\Curl;
use Yii;

class ExpressSubscribeController extends Controller
{
    protected $key;
    protected $customer;
    protected $queryUrl = 'https://poll.kuaidi100.com/poll';
    protected $callBack = 'http://dev.api.9daye.com.cn/kuaidihundred/subscribe';

    public function init(){
        $this->key = Yii::$app->params['KUAIDI100_Key'];
        $this->customer = Yii::$app->params['KUAIDI100_Customer'];
    }

    //获取需要订阅的快递单号
    public function actionSubscribeOrders(){
        //27042
        //获取需要发起订阅的订单号
        $order = Yii::$app->RQ->AR(new OrderAR())->all([
            'select'=>['id','express_corporation_id','express_number'],
            'where'=> "express_corporation_id <>0 and express_number <> ' ' and id> 27000 and is_subscribe = 0 and subscribe_num <5 ",
            'limit'=>50,
        ]);

        if (empty($order)){
            $this->stdout('当前订单物流信息已订阅完毕');
        }else{
            //获取物流公司信息
            $expressCom = Yii::$app->RQ->AR(new ExpressCorporationAR())->all([
                'select'=>['id','code'],
            ]);
            $expressCom = array_column($expressCom,'code','id');
            foreach ($order as $v){
                $this->curl($expressCom[$v['express_corporation_id']],$v['express_number'],$v['id'],$v['express_corporation_id']);
            }
        }
    }

    /**
     *====================================================
     * 发送订阅请求
     * @param $code
     * @param $expressId
     * @param $number
     * @param $orderId
     * @return bool
     * @author shuang.li
     *====================================================
     */
    private function curl($code,$number,$orderId,$expressId){
        $queryParams = [
            'schema' => 'json' ,
            'param'=>'{"company":"'.$code.'","number":"'.$number.'","from":"","to":"","key":"'.$this->key.'","parameters":{"callbackurl":"'.$this->callBack.'","salt":"'.$this->generateSign($code, $number).'","resultv2":"1","autoCom":"0","interCom":"0","departureCountry":"","departureCom":"","destinationCountry":"","destinationCom":""}}'
        ];
        $curl = new Curl();
        $curl->setDefaultJsonDecoder(true);
        $curl->post($this->queryUrl, $queryParams);
        if($curl->error)return false;
        $response = $curl->response;
        $response = is_string($response) ? json_decode($response, true) : $response;
        if (!$response['result']){
            $this->errorLog($response['returnCode'],$response['message'],$orderId,$number);
        }else{
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Yii::$app->RQ->AR(new ExpressChangeLogAR())->insert([
                    'order_id'=>$orderId,
                    'company'=>$code,
                    'number'=>$number,
                    'express_corporation_id'=>$expressId
                ]);
                (new Order(['id'=>$orderId]))->setSubscribe();
                $transaction->commit();
            }catch (\Exception $exception){
                $transaction->rollBack();
                $this->errorLog($response['returnCode'],'订阅成功，表数据写入失败',$orderId,$number);
                $this->stdout('订阅成功'.$exception->getMessage());
            }
        }
        OrderAR::updateAllCounters(['subscribe_num'=>1],['id'=>$orderId]);
    }

    /**
     *====================================================
     * 记录订阅错误信息
     * @param $returnCode
     * @param $message
     * @param $orderId
     * @param $number
     * @author shuang.li
     *====================================================
     */
    private function errorLog($returnCode,$message,$orderId,$number){
        $dir = Yii::getAlias('@app/runtime/logs');
        $filename = $dir.'/express.txt';
        if (!is_file($filename)) {
            touch($filename);
        }
        $content = "订阅失败原因：$returnCode => $message(订单号：$orderId ，'物流单号：'$number) \n";
         if (is_writable($filename)) {
            if (!$handle = fopen($filename, 'a')) {
                $this->stdout('不能打开文件');
            }
            if (fwrite($handle, $content) === FALSE) {
                $this->stdout('不能写入到文件');
            }
            fclose($handle);
        } else {
             $this->stdout("文件 $filename 不可写");
        }
    }

    /**
     *====================================================
     * 生成签名
     * @param $com
     * @param $num
     * @return string
     * @author shuang.li
     *====================================================
     */
    protected function generateSign($com, $num){
        $params = '{"com":"' . $com . '","num":"' . $num . '"}';
        return strtoupper(md5($params . $this->key . $this->customer));
    }
}