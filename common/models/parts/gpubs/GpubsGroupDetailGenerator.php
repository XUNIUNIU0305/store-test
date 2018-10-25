<?php
namespace common\models\parts\gpubs;

use Yii;
use yii\base\Object;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\models\parts\Item;

class GpubsGroupDetailGenerator extends Object{

    public static function create(GpubsGroup $group, GpubsGroupTicket $ticket, $return = 'throw'){
        if(!$group->canJoin())return Yii::$app->EC->callback($return, 'unable to join this group');
        if($ticket->status != GpubsGroupTicket::STATUS_PAID)return Yii::$app->EC->callback($return, 'unpaid ticket');
        if($group->id != $ticket->activity_gpubs_group_id)return Yii::$app->EC->callback($return, 'unmatch with group and ticket');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $insertId = Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->insert([
                'group_number' => $group->groupNumber,
                'detail_number' => (new DetailNumberGenerator)->id,
                'order_number' => 0,
                'activity_gpubs_product_id' => $group->activity_gpubs_product_id,
                'activity_gpubs_group_id' => $group->id,
                'activity_gpubs_product_sku_id' => $ticket->activity_gpubs_product_sku_id,
                'custom_user_id' => $ticket->custom_user_id,
                'custom_user_account' => $ticket->customUser->account,
                'is_owner' => $group->custom_user_id == $ticket->custom_user_id ? 1 : 0,
                'own_user_id' => $group->custom_user_id,
                'product_id' => $ticket->product_id,
                'product_title' => $ticket->product->title,
                'product_image_filename' => $ticket->product->mainImage->name,
                'product_sku_id' => $ticket->product_sku_id,
                'sku_attributes' => serialize((new Item(['id' => $ticket->product_sku_id]))->attributes),
                'quantity' => $ticket->quantity,
                'product_sku_price' => $ticket->price,
                'total_fee' => $ticket->total_fee,
                'comment' => $ticket->comment,
                'picked_up_quantity' => 0,
                'picking_up_number' => 0,
                'join_datetime' => Yii::$app->time->fullDate,
                'join_unixtime' => Yii::$app->time->unixTime,
                'cancel_datetime' => '0000-01-01 00:00:00',
                'cancel_unixtime' => 0,
                'last_pick_up_datetime' => '0000-01-01 00:00:00',
                'last_pick_up_unixtime' => 0,
                'status' => GpubsGroupDetail::STATUS_WAIT,
                'gpubs_type' => $group->gpubs_type,
                'gpubs_rule_type' => $group->gpubs_rule_type,
                'district_province_id' => $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ? $group->district_province_id : $ticket->district_province_id,
                'district_city_id' => $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE? $group->district_city_id : $ticket->district_city_id,
                'district_district_id' => $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ? $group->district_district_id : $ticket->district_district_id,
                'full_address' => $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ? $group->full_address : $ticket->full_address,
                'postal_code'=> $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ? $group->postal_code : $ticket->postal_code,
                'consignee' => $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ? $group->consignee : $ticket->consignee,
                'mobile' => $group->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ? $group->mobile : $ticket->mobile,
                'pay_method' => $ticket->pay_method,
            ], $return);
            if($insertId === $return)throw new \Exception;
            $sku = Yii::$app->db->createCommand("SELECT * FROM {{%activity_gpubs_product_sku}} WHERE [[id]] = :id FOR UPDATE")->bindValues([
                ':id' => $ticket->activity_gpubs_product_sku_id,
            ])->queryOne();
            if($sku['stock'] < $ticket->quantity)throw new \Exception;
            $result = Yii::$app->db->createCommand("UPDATE {{%activity_gpubs_product_sku}} SET [[stock]] = [[stock]] - :quantity WHERE [[id]] = :id")->bindValues([
                ':quantity' => $ticket->quantity,
                ':id' => $ticket->activity_gpubs_product_sku_id,
            ])->execute();
            if(!$result)throw new \Exception;
            $gpubsGroupDetail = new GpubsGroupDetail([
                'id' => $insertId,
            ]);
            //$group->join($gpubsGroupDetail);
            //$ticket->joined = true;
            $transaction->commit();
            return $gpubsGroupDetail;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
