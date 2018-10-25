<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 14:28
 */

namespace common\components\handler;

use business\models\parts\Area;
use common\models\parts\MembraneOrder;
use common\ActiveRecord\MembraneOrderAR;
use common\models\parts\MembraneOrderItemObject;
use custom\models\parts\MembraneOrderIdGenerator;
use common\models\parts\Address;
use common\models\parts\custom\CustomUser;
use yii\web\BadRequestHttpException;

class MembraneOrderHandler extends Handler
{
    /**
     * 批量创建订单
     * @param array $items
     * @param Address $address
     * @param CustomUser $user
     * @return array
     * @throws \Exception
     */
    public static function batchInsert(array $items, Address $address, CustomUser $user)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $res = [];
            $area = $user->getArea();
            static::getLevel($area, $areaLevel, $uid);
            /** @var MembraneOrderItemObject $item */
            foreach ($items as $item) {
                $entity = new MembraneOrderAR;
                $entity->order_number = (new MembraneOrderIdGenerator(['length' => 11]))->getId();
                $entity->remark = $item->remark;
                $entity->business_top_area_id = $areaLevel[1];
                $entity->business_second_area_id = $areaLevel[2];
                $entity->business_third_area_id = $areaLevel[3];
                $entity->business_fourth_area_id = $areaLevel[4];
                $entity->business_fifth_area_id = $areaLevel[5];
                $entity->business_third_user_id = $uid;
                $entity->total_fee = $item->price;
                $entity->receive_address = (string)$address;
                $entity->receive_mobile = $address->getMobile();
                $entity->receive_code = $address->getPostalCode();
                $entity->receive_name = $address->getConsignee();
                $entity->custom_user_id = $user->id;
                $entity->custom_user_account = $user->account;
                $entity->insert();
                if(!$item->save($entity->id))
                    throw new BadRequestHttpException('参数验证失败');
                $res[] = new MembraneOrder(['AR' => $entity]);
            }
            $transaction->commit();
            return $res;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public static function getLevel(Area $area, &$res, &$uid)
    {
        $level = $area->getLevel()->level;
        if ($level > Area::LEVEL_TOP){
            static::getLevel($area->getParent(), $res, $uid);
        }
        if($level === Area::LEVEL_QUATERNARY){
            $uid = $area->getLeader() ? $area->getLeader()->id : 0;
        }
        $res[$level] = $area->id;
    }
}
