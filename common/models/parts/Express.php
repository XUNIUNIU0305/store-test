<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\ExpressCorporationAR;
use Curl\Curl;

class Express extends Object{

    //Object \common\models\Order
    public $order;

    protected $key;
    protected $customer;
    protected $queryUrl = 'http://poll.kuaidi100.com/poll/query.do';

    public function init(){
        $this->key = Yii::$app->params['KUAIDI100_Key'];
        $this->customer = Yii::$app->params['KUAIDI100_Customer'];
    }

    /**
     * 获取查询信息
     *
     * @param integer $expressCorporationId 快递公司ID database table:pf_express_corporation
     * @param string $expressNumber 快递单号
     *
     * @return array
     */
    public function getDetail($expressCorporationId = null, $expressNumber = null){
        if(!is_null($expressCorporationId) && !is_null($expressNumber)){
            $num = $expressNumber;
        }elseif($this->order instanceof Order){
            $expressCorporationId = $this->order->expressCorp;
            $num = $this->order->expressNo;
        }else{
            return false;
        }
        $com = Yii::$app->RQ->AR(new ExpressCorporationAR)->scalar([
            'select' => ['code'],
            'where' => ['id' => $expressCorporationId],
        ]);
        return $this->queryExpress($com, $num);
    }

    /**
     * 查询快递信息
     *
     * @param string $com 快递公司CODE；快递100指定
     * @param string $num 快递单号
     *
     * @return array
     */
    public function queryExpress(string $com, string $num){
        $queryParams = [
            'customer' => $this->customer,
            'sign' => $this->generateSign($com, $num),
            'param' => '{"com":"' . $com . '","num":"' . $num . '"}',
        ];
        $curl = new Curl();
        $curl->setDefaultJsonDecoder(true);
        $curl->get($this->queryUrl, $queryParams);
        if($curl->error)return false;
        $response = $curl->response;
        $response = is_string($response) ? json_decode($response, true) : $response;
        if($response['message'] != 'ok')return false;
        return [
            'com' => $response['com'] ?? '',
            'nu' => $response['nu'] ?? '',
            'detail' => $response['data'],
        ];
    }

    /**
     * 生成查询加密签名
     *
     * @return string
     */
    protected function generateSign($com, $num){
        $params = '{"com":"' . $com . '","num":"' . $num . '"}';
        return strtoupper(md5($params . $this->key . $this->customer));
    }

    public function getExpressName(){
        $com = Yii::$app->RQ->AR(new ExpressCorporationAR)->scalar([
            'select' => ['code'],
            'where' => ['id' => $expressCorporationId],
        ]);
    }

}
