<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/10
 * Time: 下午5:30
 */

namespace api\models;


use api\components\handler\KuaidihundredHandler;
use common\ActiveRecord\ExpressChangeLogAR;
use common\ActiveRecord\OrderAR;
use common\models\Model;
use common\models\parts\ExpressChangeLog;
use common\models\parts\Order;
use Yii;

class KuaidihundredModel extends Model
{
    const SCE_SUBSCRIBE = 'subscribe';
    public $param;
    public $sign;


    public function scenarios()
    {
        return [
            self::SCE_SUBSCRIBE => [
                'param',
                'sign'
            ],
        ];
    }

    public function subscribe()
    {
        $expressInfo = json_decode($this->param, true);
        $changeLog = serialize($expressInfo['lastResult']['data']);
        $expressNumber = $expressInfo['lastResult']['nu'];
        $expressCom = $expressInfo['lastResult']['com'];
        $salt = $this->generateSign($expressCom, $expressNumber);

        //监控状态:polling:监控中，shutdown:结束，abort:中止
        if (strtoupper(md5($this->param . $salt)) == $this->sign)
        {
            try
            {
                if (!Yii::$app->RQ->AR(new ExpressChangeLogAR())->exists(['where' => ['number' => $expressNumber]]))
                {
                    return ['message' => '该物流单号未订阅'];
                }
                else
                {
                    $expressChangeLogModel = new ExpressChangeLog(['number' => $expressNumber]);
                }

                switch ($expressInfo['status'])
                {
                case 'polling':
                case 'shutdown':
                    if ($expressChangeLogModel->setChangeLog($changeLog)!== false) return ['message' => '推送回调成功'];break;
                case 'abort':
                    //三天查询不到记录
                    if (strpos($expressInfo['message'], '3天') >= 0)
                    {
                        //快递公司编码正确
                        if (empty($expressInfo['comNew']))
                        {
                            //如果重新订阅失败次数超过 超过4次 则不需要再订阅 此单为假单
                            $subscribeInfo = Yii::$app->RQ->AR(new OrderAR())->one([
                                'select' => [
                                    'id',
                                    'subscribe_num'
                                ],
                                'where' => ['express_number' => $expressNumber]
                            ]);

                            if ($subscribeInfo['subscribe_num'] >= 4)
                            {
                                //此单为假单
                                if ($expressChangeLogModel->reason = '此单为假单' !== false) return ['message' => '推送回调成功,已标注此单为假单'];
                            }
                            else
                            {
                                //重新发起订阅
                                $orderModel = new Order(['id'=>$subscribeInfo['id']]);
                                if($orderModel->restartSubscribe = bcadd($subscribeInfo['subscribe_num'], 1)!==false) return ['message' => '推送回调成功,已重新发起订阅'];
                            }
                        }
                        else
                        {
                            //快递公司编码错误
                            if ($expressChangeLogModel->reason = '快递公司编码错误，更新后编码为：' . $expressInfo['comNew'] !== false) return ['message' => '推送回调成功,快递公司编码错误'];
                        }
                    }
                    //60无跟踪记录
                    elseif (strpos($expressInfo['message'], '60天') >= 0)
                    {
                        if ($expressChangeLogModel->reason = '60天无跟踪记录' !== false) return ['message' => '推送回调成功,60天无跟踪记录'];
                    }
                    break;
                }

            }
            catch (\Exception $exception)
            {
                return ['message' => '推送回调失败'];
            }
        }
        return ['message' => '非法请求'];
    }

    protected function generateSign($com, $num)
    {
        $params = '{"com":"' . $com . '","num":"' . $num . '"}';
        return strtoupper(md5($params . Yii::$app->params['KUAIDI100_Key'] . Yii::$app->params['KUAIDI100_Customer']));
    }


}