<?php
namespace common\models\parts\gpubs;

use Yii;
use yii\base\Object;
use common\ActiveRecord\ActivityGpubsGroupTicketAR;
use common\models\parts\custom\CustomUser;
use custom\models\parts\trade\PaymentMethod;

class GpubsGroupTicketGenerator extends Object{

    public static function create(GpubsGroup $group, GpubsProductSku $sku, CustomUser $user,PaymentMethod $paymentMethod,$address = 0, int $quantity, string $comment, $return = 'throw'){
        if(!$group->canJoin())return Yii::$app->EC->callback($return, 'unable to join the group');
        if($group->activity_gpubs_product_id != $sku->activityGpubsProductId)return Yii::$app->EC->callback($return, 'unmatch with group and sku');
        if($quantity <= 0)return Yii::$app->EC->callback($return, 'invalid product quantity');
        $insertId = Yii::$app->RQ->AR(new ActivityGpubsGroupTicketAR)->insert([
            'activity_gpubs_product_id' => $group->activity_gpubs_product_id,
            'activity_gpubs_product_sku_id' => $sku->id,
            'activity_gpubs_group_id' => $group->id,
            'custom_user_id' => $user->id,
            'product_id' => $sku->product_id,
            'product_sku_id' => $sku->productSkuId,
            'quantity' => $quantity,
            'price' => $sku->price,
            'total_fee' => $sku->price * $quantity,
            'comment' => $comment,
            'create_datetime' => Yii::$app->time->fullDate,
            'create_unixtime' => Yii::$app->time->unixTime,
            'pay_datetime' => '0000-01-01 00:00:00',
            'pay_unixtime' => 0,
            'status' => GpubsGroupTicket::STATUS_UNPAID,
            'is_join' => GpubsGroupTicket::JOIN_FAILED,
            'district_province_id' => $group->gpubs_type == 1 ? 0 :  $address->province,
            'district_city_id' =>  $group->gpubs_type == 1 ? 0 : $address->city,
            'district_district_id' =>  $group->gpubs_type == 1 ? 0 : $address->district,
            'detailed_address' =>  $group->gpubs_type == 1 ? '': $address->detail,
            'full_address' =>  $group->gpubs_type == 1 ? '': strval($address),
            'postal_code' => $group->gpubs_type == 1 ? '0' : $address->postalcode,
            'consignee' => $group->gpubs_type == 1 ? '' :  $address->consignee,
            'mobile' =>  $group->gpubs_type == 1 ? 0 : $address->mobile,
            'pay_method' => $paymentMethod->currentPaymentMethod,
        ], $return);
        if($insertId === $return)return $return;
        return new GpubsGroupTicket([
            'id' => $insertId,
        ]);
    }
}
