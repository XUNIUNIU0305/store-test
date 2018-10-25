<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/12
 * Time: 18:47
 */

namespace common\models\parts;

use yii\base\Object;
use common\ActiveRecord\OrderCustomizationAR;
use common\ActiveRecord\OrderCustomizationNoteAR;
use common\ActiveRecord\OrderCustomizationPicAR;
use common\models\parts\car\CarBrand;
use common\models\parts\car\CarType;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\web\BadRequestHttpException;

/**
 * @property int $id
 * @property Order $order
 * @property [] $pics
 * @property [] $notes
 * Class OrderCustomization
 * @package common\models\parts
 */
class OrderCustomization extends Object
{
    const STATUS_DEFAULT = 1;              //未上传
    const STATUS_UN_FINISH = 2;            //未处理
    const STATUS_IN_PRODUCE = 3;               //已处理|生产中
    const STATUS_SEND = 4;                  //已发货
    const STATUS_REJECTED = 5;               //已拒绝
    const STATUS_CANCELED = 6;              //已取消

    const NOTE_TYPE_DEFAULT = 1;          //备注用户类型(custom)
    const NOTE_TYPE_SUPPLY = 2;

    public static $status = [
        self::STATUS_DEFAULT => '未上传',
        self::STATUS_UN_FINISH => '未处理',
        self::STATUS_IN_PRODUCE => '生产中',
        self::STATUS_SEND => '已发货',
        self::STATUS_REJECTED => '已拒绝',
        self::STATUS_CANCELED => '已取消'
    ];

    public $order_number;

    public $id;

    private $AR;

    public function init()
    {
        if(!$this->order_number && !$this->id) throw new InvalidConfigException;
        if($this->order_number){
            if(!$this->AR = OrderCustomizationAR::findOne(['order_number'=>$this->order_number])) throw new InvalidConfigException;
            $this->id = $this->AR->id;
        } elseif ($this->id) {
            if(!$this->AR = OrderCustomizationAR::findOne(['id'=>$this->id])) throw new InvalidConfigException;
            $this->order_number = $this->AR->order_number;
        }

    }

    public function setAR($ar)
    {
        $this->AR = $ar;
    }

    /**
     * 主订单
     * @return Order
     */
    public function getOrder()
    {
        return new Order(['orderNumber' => $this->order_number]);
    }

    /**
     * 图片
     * @return mixed
     */
    public function getPics()
    {
        return \Yii::$app->RQ->AR(new OrderCustomizationPicAR)->all([
            'select' => ['id', 'upload_filename as filename'],
            'where'  => ['order_customization_id' => $this->id]
        ]);
    }

    /**
     * 留言
     * @return mixed
     */
    public function getNotes()
    {
        return \Yii::$app->RQ->AR(new OrderCustomizationNoteAR)->all([
            'select' => ['id','text', 'type'],
            'where' => ['order_customization_id' => $this->id],
            'orderBy' => [
                'created' => SORT_DESC
            ]
        ]);
    }

    /**
     * 添加备注
     * @param array $attributes
     */
    public function sendNote(array $attributes)
    {
        $model = new OrderCustomizationNoteAR;
        foreach ($attributes as $key=>$attribute){
            $model->$key = $attribute;
        }
        $model->order_customization_id = $this->id;
        $model->insert();
    }

    /**
     * 接单
     * @return bool
     * @throws BadRequestHttpException
     */
    public function holdOrder()
    {
        if($this->AR->status === self::STATUS_UN_FINISH){
            $this->AR->status = self::STATUS_IN_PRODUCE;
            $this->AR->accept_date = new Expression('now()');
            $this->AR->update();
            return true;
        }
        throw new BadRequestHttpException('');
    }

    /**
     * 拒单
     * @return bool
     * @throws BadRequestHttpException
     */
    public function rejectOrder()
    {
        if($this->AR->status === self::STATUS_UN_FINISH){
            $this->AR->status = self::STATUS_REJECTED;
            $this->AR->reject_date = new Expression('now()');
            $this->AR->update();
            return true;
        }
        throw new BadRequestHttpException('状态不可拒绝');
    }

    /**
     * 取消订单
     * @throws BadRequestHttpException
     */
    public function cancelOrder()
    {
        if($this->AR->status < self::STATUS_IN_PRODUCE ){
            $this->AR->status = self::STATUS_CANCELED;
            $this->AR->cancel_date = new Expression('now()');
            $this->AR->update();
            return true;
        }
        throw new BadRequestHttpException();
    }

    /**
     * 发货
     * @param $expressCorporation
     * @param $expressNumber
     * @return bool
     * @throws BadRequestHttpException
     */
    public function ship($expressCorporation, $expressNumber)
    {
        if ($this->AR->status === self::STATUS_IN_PRODUCE){
            $order = new Order([
                'orderNumber' => $this->order_number,
                'expressCorporation' => $expressCorporation,
                'expressNumber' => $expressNumber
            ]);
            $order->setStatus(Order::STATUS_DELIVERED);
            $this->AR->ship_date = new Expression('now()');
            $this->AR->status = self::STATUS_SEND;
            $this->AR->update();
            return true;
        }
        throw new BadRequestHttpException('');
    }

    /**
     * 订单上传/修改
     * @param $brand_id
     * @param $type_id
     * @return bool
     * @throws BadRequestHttpException
     */
    public function upload($brand_id, $type_id)
    {
        if(in_array($this->AR->status, [self::STATUS_DEFAULT, self::STATUS_UN_FINISH])){
            $this->AR->car_brand_id = $brand_id;
            $this->AR->car_type_id = $type_id;
            if($this->AR->status == self::STATUS_DEFAULT){
                //上传
                $this->AR->upload_date = new Expression('now()');
                $this->AR->status = self::STATUS_UN_FINISH;
            } else {
                //修改
                $this->AR->update_date = new Expression('now()');
            }
            $this->AR->update();
            return true;
        }
        throw new BadRequestHttpException();
    }

    /**
     * 更新图片
     * @param $images
     * @param $uid
     */
    public function updateImages($images, $uid)
    {
        \Yii::$app->db->createCommand('DELETE FROM ' . OrderCustomizationPicAR::tableName() . ' WHERE order_customization_id = :id', ['id' => $this->id])
            ->execute();

        \Yii::$app->db->createCommand()->batchInsert(OrderCustomizationPicAR::tableName(), [
            'order_customization_id',
            'upload_user_id',
            'upload_filename'
        ],array_map(function($url) use($uid){
            return [
                $this->id,
                $uid,
                $url
            ];
        }, $images))->execute();
    }

    /**
     * @return int
     */
    public function getCustomAccount()
    {
        return $this->getOrder()->getCustomerAccount();
    }

    public function getUpdateDate()
    {
        return $this->AR->update_date ?? '';
    }

    public function getUploadDate()
    {
        return $this->AR->upload_date ?? '';
    }

    public function getAcceptDate()
    {
        return $this->AR->accept_date ?? '';
    }

    public function getRejectDate()
    {
        return $this->AR->reject_date ?? '';
    }

    public function getShipDate()
    {
        return $this->AR->ship_date ?? '';
    }

    public function getCarBrandId()
    {
        return $this->AR->car_brand_id;
    }

    private $carBrand;
    public function getCarBrand()
    {
        if($this->carBrand === null && $this->AR->car_brand_id)
            $this->carBrand = new CarBrand(['id' => $this->AR->car_brand_id]);
        return $this->carBrand;
    }

    public function getCarBrandName()
    {
        return $this->getCarBrand()->name ?? '';
    }

    public function getCarBrandImage()
    {
        if($this->getCarBrand()){
            return \Yii::$app->params['OSS_PostHost'] . $this->getCarBrand()->getLogo();
        }
        return '';
    }

    public function getCarTypeId()
    {
        return $this->AR->car_type_id;
    }

    private $carType;
    public function getCarType()
    {
        if($this->carType === null && $this->AR->car_type_id)
            $this->carType = new CarType(['id' => $this->AR->car_type_id]);
        return $this->carType;
    }

    public function getCarTypeName()
    {
        return $this->getCarType()->name ?? '';
    }

    public function getStatus()
    {
        return $this->AR->status;
    }
}