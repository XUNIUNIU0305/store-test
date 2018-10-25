<?php
namespace common\models\parts\gpubs;

use Yii;
use yii\base\Object;
use common\models\parts\custom\CustomUser;
use common\models\parts\gpubs\GpubsAddress;
use common\ActiveRecord\ActivityGpubsGroupAR;

class GpubsGroupGenerator extends Object{

    public static function create(GpubsProduct $product, CustomUser $user, $address, $return = 'throw'){
        if(Yii::$app->time->unixTime < $product->activity_start_unixtime || Yii::$app->time->unixTime >= $product->activity_end_unixtime)return Yii::$app->EC->callback($return, 'out of range of active time');
        $groupEndUnixtime = $product->lifecycle_per_group + Yii::$app->time->unixTime > $product->activity_end_unixtime ? $product->activity_end_unixtime : $product->lifecycle_per_group + Yii::$app->time->unixTime;
        $insertId = Yii::$app->RQ->AR(new ActivityGpubsGroupAR)->insert([
            'group_number' => (new GroupNumberGenerator)->id,
            'activity_gpubs_product_id' => $product->id,
            'custom_user_id' => $user->id,
            'supply_user_id' => $product->getProduct()->getSupplier(),
            'group_start_datetime' => date('Y-m-d H:i:s'),
            'group_start_unixtime' => time(),
            'group_end_datetime' => date('Y-m-d H:i:s', $groupEndUnixtime),
            'group_end_unixtime' => $groupEndUnixtime,
            'group_establish_datetime' => '0000-01-01 00:00:00',
            'group_establish_unixtime' => 0,
            'group_cancel_datetime' => '0000-01-01 00:00:00',
            'group_cancel_unixtime' => 0,
            'group_deliver_datetime' => '0000-01-01 00:00:00',
            'group_deliver_unixtime' => 0,
            'target_quantity' => $product->min_quantity_per_group,
            'present_quantity' => 0,
            'target_member' => $product->min_member_per_group,
            'present_member' => 0,
            'picked_up_quantity' => 0,
            'total_fee' => 0,
            'district_province_id' => $address->province,
            'district_city_id' => $address->city,
            'district_district_id' => $address->district,
            'detailed_address' => $address->detail,
            'mobile' => $address->mobile,
            'consignee' => $address->consignee,
            'full_address' => strval($address),
            'status' => GpubsGroup::STATUS_UNPAID,
            'spot_name' => isset($address->spotName) ? $address->spotName : '0',
            'gpubs_type' => $product->gpubs_type,
            'gpubs_rule_type' => $product->gpubs_rule_type,
            'postal_code' =>$address->postalcode,
            'min_quantity_per_member_of_group' => $product->min_quantity_per_member_of_group,
        ], $return);
        if($insertId === $return)return $return;
        return new GpubsGroup([
            'id' => $insertId,
        ]);
    }
}
