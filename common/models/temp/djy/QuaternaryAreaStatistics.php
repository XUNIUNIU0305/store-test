<?php
namespace common\models\temp\djy;

use Yii;

class QuaternaryAreaStatistics extends Statistics{

    public function getTotal($areaId){
        return $this->getCache('djy_q_total' . (string)$areaId, 'achieveTotal', [$areaId]);
    }

    public function achieveTotal($areaId){
        return Yii::$app->db->createCommand("SELECT SUM(`item`.[[total_fee]]) FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[quaternary_area_id]] = :areaId")->bindValues([
            ':start' => $this->getDjy()->getStart(),
            ':end' => $this->getDjy()->getEnd(),
            ':areaId' => $areaId,
        ])->
        queryScalar();
    }

    public function getSku($areaId){
        return $this->getCache('djy_q_sku' . (string)$areaId, 'achieveSku', [$areaId]);
    }

    public function achieveSku($areaId){
        $result = Yii::$app->db->createCommand("SELECT `item`.[[product_sku_id]] AS `product_sku_id`, `item`.[[sku_attributes]] AS `sku_attributes`, SUM(`item`.[[count]]) AS `quantity` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[quaternary_area_id]] = :areaId GROUP BY `item`.[[product_sku_id]] ORDER BY `quantity` DESC")->
            bindValues([
                ':start' => $this->getDjy()->getStart(),
                ':end' => $this->getDjy()->getEnd(),
                ':areaId' => $areaId,
            ])->
            queryAll();
        return array_map(function($one){
            $one['sku_attributes'] = unserialize($one['sku_attributes']);
            return $one;
        }, $result);
    }

    public function getList($areaId, int $currentPage = 1, $pageSize = 10){
        return $this->achieveList($areaId, $currentPage, $pageSize);
    }

    public function achieveList($areaId, int $currentPage = 1, int $pageSize = 10){
        $count = Yii::$app->db->createCommand("SELECT `user`.[[account]] FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] JOIN {{%custom_user}} AS `user` ON `item`.[[custom_user_id]] = `user`.[[id]] LEFT JOIN {{%custom_user_address}} AS `address` ON `user`.[[id]] = `address`.[[custom_user_id]] AND `address`.[[default]] = 1 WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[quaternary_area_id]] = :areaId GROUP BY `item`.[[custom_user_id]]")->bindValues([
            ':start' => $this->getDjy()->getStart(),
            ':end' => $this->getDjy()->getEnd(),
            ':areaId' => $areaId,
        ])->
        queryAll();
        $count = count($count);
        if($count == 0){
            return [
                'count' => 0,
                'total_count' => 0,
                'list' => [],
            ];
        }
        $offset = ($currentPage - 1) * $pageSize;
        $result = Yii::$app->db->createCommand("SELECT `user`.[[account]] AS `account`, `user`.[[shop_name]] AS `shop_name`, IFNULL(`address`.[[consignee]], '') AS `consignee`, IFNULL(`address`.[[mobile]], '') AS `mobile`, SUM(`item`.[[total_fee]]) AS `total_fee` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] JOIN {{%custom_user}} AS `user` ON `item`.[[custom_user_id]] = `user`.[[id]] LEFT JOIN {{%custom_user_address}} AS `address` ON `user`.[[id]] = `address`.[[custom_user_id]] AND `address`.[[default]] = 1 WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[quaternary_area_id]] = :areaId GROUP BY `item`.[[custom_user_id]] ORDER BY `total_fee` DESC LIMIT {$offset}, {$pageSize}")->bindValues([
            ':start' => $this->getDjy()->getStart(),
            ':end' => $this->getDjy()->getEnd(),
            ':areaId' => $areaId,
        ])->
        queryAll();
        return [
            'count' => count($result),
            'total_count' => $count,
            'list' => $result,
        ];
    }

    public function getCommander($areaId){
        return $this->getCache('djy_q_commander' . (string)$areaId, 'achieveCommander', [$areaId]);
    }

    public function achieveCommander($areaId){
        $commanderList = $this->getDjy()->getCommanders()->getQuaternaryAreaCommander();
        if($account = $commanderList[$areaId] ?? null){
            return Yii::$app->db->createCommand("SELECT `address`.[[consignee]] AS `consignee`, `address`.[[mobile]] AS `mobile` FROM {{%custom_user}} AS `user` LEFT JOIN {{%custom_user_address}} AS `address` ON `user`.[[id]] = `address`.[[custom_user_id]] AND `address`.[[default]] = 1 WHERE `user`.[[account]] = :account")->bindValues([
                ':account' => $account,
            ])->
            queryOne();
        }else{
            return [
                'consignee' => '',
                'mobile' => '',
            ];
        }
    }
}
