<?php
namespace common\models\temp\djy;

use Yii;

class GlobalStatistics extends Statistics{

    public function getTotal(){
        return $this->getCache('djy_g_total', 'achieveTotal');
    }

    public function achieveTotal(){
        return Yii::$app->db->createCommand("SELECT SUM(`item`.[[total_fee]]) AS `total_fee`, SUM(`item`.[[count]]) AS `quantity` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds})")->
            bindValues([
                ':start' => $this->getDjy()->getStart(),
                ':end' => $this->getDjy()->getEnd(),
            ])->
            queryOne();
    }

    public function getSku(){
        return $this->getCache('djy_g_sku', 'achieveSku');
    }

    public function achieveSku(){
        $result = Yii::$app->db->createCommand("SELECT `item`.[[product_sku_id]] AS `product_sku_id`, `item`.[[sku_attributes]] AS `sku_attributes`, SUM(`item`.[[count]]) AS `quantity` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) GROUP BY `item`.[[product_sku_id]] ORDER BY `quantity` DESC")->
            bindValues([
                ':start' => $this->getDjy()->getStart(),
                ':end' => $this->getDjy()->getEnd(),
            ])->
            queryAll();
        return array_map(function($one){
            $one['sku_attributes'] = unserialize($one['sku_attributes']);
            return $one;
        }, $result);
    }

    public function getTopAreaTotal(){
        return $this->getCache('djy_g_topAreaTotal', 'achieveTopAreaTotal');
    }

    public function achieveTopAreaTotal(){
        return Yii::$app->db->createCommand("SELECT `area`.[[id]] AS `area_id`, `area`.[[name]] AS `area_name`, SUM(`item`.[[total_fee]]) AS `total_fee` FROM {{%order_item}} AS `item` JOIN {{%order}} AS `order` ON `item`.[[order_id]] = `order`.[[id]] JOIN {{%order_business_record}} AS `record` ON `order`.[[id]] = `record`.[[order_id]] JOIN {{%business_area}} AS `area` ON `record`.[[top_area_id]] = `area`.[[id]] WHERE `order`.[[pay_datetime]] BETWEEN :start AND :end AND `order`.[[status]] IN ({$this->status}) AND `item`.[[product_sku_id]] IN ({$this->skuIds}) GROUP BY `record`.[[top_area_id]] ORDER BY `total_fee` DESC")->
            bindValues([
                ':start' => $this->getDjy()->getStart(),
                ':end' => $this->getDjy()->getEnd(),
            ])->
            queryAll();
    }
}
