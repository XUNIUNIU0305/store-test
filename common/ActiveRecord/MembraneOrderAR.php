<?php
/**
 * 膜订单表
 * User: wangli
 */

namespace common\ActiveRecord;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class MembraneOrderAR
 * @package common\ActiveRecord
 * @property int $id
 * @property int $order_number
 * @property int $custom_user_id
 * @property int $custom_user_account
 * @property float $total_fee
 * @property string $receive_name
 * @property string $receive_address
 * @property string $receive_mobile
 * @property string $receive_code
 * @property string $remark
 * @property string $created_date
 * @property string $pay_date
 * @property string $accept_date
 * @property string $finish_date
 * @property string $fill_date
 * @property int $business_top_area_id
 * @property int $business_second_area_id
 * @property int $business_third_area_id
 * @property int $business_fourth_area_id
 * @property int $business_fifth_area_id
 * @property int $business_third_user_id
 * @property int $business_third_area_leader_id
 * @property int $status
 */
class MembraneOrderAR extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_date'
                ],
                'value' => new Expression('NOW()')
            ]
        ];
    }

    public static function tableName()
    {
        return '{{%membrane_order}}';
    }
}