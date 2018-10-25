<?php
namespace common\models\parts;


use common\ActiveRecord\OrderRefundAR;
use common\models\parts\custom\CustomUser;
use common\models\parts\order\OrderRefund;
use common\models\parts\supply\SupplyUser;
use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use common\ActiveRecord\OrderItemAR;

class ItemInOrder extends Object{
    
    //订单商品表主键
    public $id;

    protected $AR;
    protected $item;

    public function init(){
        if(!$this->id ||
            !$this->AR = OrderItemAR::findOne($this->id)
        )throw new InvalidCallException;
    }

    /**
     * 获取订单ID
     *
     * @return int
     */
    public function getOrderId(){
        return $this->AR->order_id;
    }

    /**
     * 获取购买用户ID
     *
     * @return int
     */
    public function getCustomerId(){
        return $this->AR->custom_user_id;
    }
    //获了客户信息
    public function getCustomer(){
        return new CustomUser(['id'=>$this->AR->custom_user_id]);
    }

    /**
     * 获取销售用户ID
     *
     * @return int
     */
    public function getSupplierId(){
        return $this->AR->supply_user_id;
    }

    //获取供应商类
    public function getSupplier(){
        return new SupplyUser(['id'=>$this->AR->supply_user_id]);
    }

    /**
     * 获取Item ID
     *
     * @return int
     */
    public function getItemId(){
        return $this->AR->product_sku_id;
    }

    /**
     * 获取Item 对象
     *
     * @return Object Item
     */
    public function getItem(){
        try{
            if(is_null($this->item)){
                $this->item = new Item(['id' => $this->AR->product_sku_id]);
            }
        }catch(\Exception $e){
            $this->item = false;
        }
        return $this->item;
    }

    /**
     * 获取商品总价
     *
     * @return float
     */
    public function getTotalFee(){
        return $this->AR->total_fee;
    }

    /**
     * 获取商品标题
     *
     * @return string
     */
    public function getTitle(){
        return $this->AR->title;
    }

    /**
     * 获取商品属性
     *
     * @return array
     */
    public function getSKUAttributes(){
        return unserialize($this->AR->sku_attributes);
    }

    /**
     * 获取商品单价
     *
     * @return float
     */
    public function getPrice(){
        return (float)$this->AR->price;
    }

    /**
     * 获取商品数量
     *
     * @return int
     */
    public function getCount(){
        return (int)$this->AR->count;
    }

    /**
     * 获取商品图片对象
     *
     * @return Object OSSImage
     */
    public function getImage(){
        return new OSSImage(['images' => $this->AR->oss_upload_file_id]);
    }

    /**
     * 获取自定义ID
     *
     * @return string
     */
    public function getCustomId(){
        if($item = $this->item){
            return $item->customId;
        }else{
            return $this->AR->custom_id;
        }
    }



    /**
     * 获取条形码
     *
     * @return string
     */
    public function getBarCode(){
        if($item = $this->item){
            return $item->barCode;
        }else{
            return $this->AR->bar_code;
        }
    }

    /*
     * Mod:jiangi
     * Date:2017/3/31
     * Desc;获取备注信息
     */
    public function getComments(){
        return $this->AR->comments;
    }

    /*
     * Mod:jiangyi
     * Date:2017/04/24
     * Desc: cwg
     */
    public function refundExists(){
        return OrderRefundAR::find()->
            where(['order_item_id' => $this->id])->
            andWhere(['not in', 'status', [
                OrderRefund::REFUND_STATUS_REJECT,
                OrderRefund::REFUND_STATUS_REFUND_MONEY,
                OrderRefund::REFUND_STATUS_FINISHED,
                OrderRefund::REFUND_STATUS_CANCEL,
            ]])->
            exists();
    }

    //获取已退换数量
    public function getRefundQuantity(){
        return OrderRefundAR::find()->where("order_item_id='$this->id'")->sum("quantity");
    }


    /*获取订单信息*/
    public function getOrder(){
        return new Order(['id'=>$this->AR->order_id]);
    }
}
