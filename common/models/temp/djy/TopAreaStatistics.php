<?php
namespace common\models\temp\djy;

use Yii;

class TopAreaStatistics extends Statistics{

    public function getTotal($areaId){
        return $this->getCache('djy_t_total' . (string)$areaId, 'achieveTotal', [$areaId]);
    }

    public function achieveTotal($areaId){
        return Yii::$app->db->createCommand("SELECT SUM(`item`.[[total_fee]]) FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[top_area_id]] = :areaId")->bindValues([
            ':start' => $this->getDjy()->getStart(),
            ':end' => $this->getDjy()->getEnd(),
            ':areaId' => $areaId,
        ])->
        queryScalar();
    }

    public function getSku($areaId){
        return $this->getCache('djy_t_sku' . (string)$areaId, 'achieveSku', [$areaId]);
    }

    public function achieveSku($areaId){
        $result = Yii::$app->db->createCommand("SELECT `item`.[[product_sku_id]] AS `product_sku_id`, `item`.[[sku_attributes]] AS `sku_attributes`, SUM(`item`.[[count]]) AS `quantity` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[top_area_id]] = :areaId GROUP BY `item`.[[product_sku_id]] ORDER BY `quantity` DESC")->
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

    public function getList($areaId){
        return $this->getCache('djy_t_list' . (string)$areaId, 'achieveList', [$areaId]);
    }

    public function achieveList($areaId){
        return Yii::$app->db->createCommand("SELECT `record`.[[quaternary_area_id]] AS `area_id`, `area`.[[name]] AS `area_name`, SUM(`item`.[[total_fee]]) AS `total_fee` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] JOIN {{%business_area}} AS `area` ON `record`.[[quaternary_area_id]] = `area`.[[id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) AND `record`.[[top_area_id]] = :areaId GROUP BY `record`.[[quaternary_area_id]] ORDER BY `total_fee` DESC")->
            bindValues([
                ':start' => $this->getDjy()->getStart(),
                ':end' => $this->getDjy()->getEnd(),
                ':areaId' => $areaId,
            ])->
            queryAll();
    }
}
